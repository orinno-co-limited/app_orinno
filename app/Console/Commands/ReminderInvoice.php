<?php

namespace App\Console\Commands;

use App\Models\EmailTemplate;
use App\Models\Invoice;
use App\Services\Notification\NotificationService;
use App\Services\SmsMail\MailService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReminderInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reminder invoice for tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (getOption('remainder_status', 0) != REMAINDER_STATUS_ACTIVE) {
                throw new Exception('reminder status inactive');
            }

            $everyday = getOption('remainder_everyday_status') == REMAINDER_EVERYDAY_STATUS_ACTIVE;
            $reminderDaysArray = explode(',', getOption('reminder_days'));
            $maxReminders = (int) getOption('reminder_max_count', 3);

            // due today or overdue - reminders chase unpaid rent, they don't announce upcoming due dates
            $invoices = Invoice::query()
                ->where('status', INVOICE_STATUS_PENDING)
                ->where('due_date', '<=', today())
                ->get();

            foreach ($invoices as $invoice) {
                try {
                    if ($invoice->reminder_count >= $maxReminders) {
                        $invoice->status = INVOICE_STATUS_OVER_DUE;
                        $invoice->save();
                        continue;
                    }

                    if (!$everyday) {
                        $diffDay = Carbon::parse($invoice->due_date)->diffInDays(today());
                        if (!in_array((string) $diffDay, $reminderDaysArray)) {
                            continue;
                        }
                    }

                    $this->sendReminder($invoice);

                    $invoice->reminder_count = $invoice->reminder_count + 1;
                    $invoice->last_reminded_at = now();
                    $invoice->save();
                } catch (Exception $e) {
                    Log::info('auto reminder error for invoice ' . $invoice->id . ': ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Log::info('auto reminder error: ' . $e->getMessage());
        }
    }

    private function sendReminder(Invoice $invoice)
    {
        $subject = __('Payment reminder') . ' ' . $invoice->invoice_no . ' ' . __('due on date') . ' ' . $invoice->due_date;
        $title = __('Payment reminder!');
        $message = __('You have a due invoice');
        $ownerUserId = $invoice->owner_user_id;
        $amount = $invoice->amount;
        $dueDate = $invoice->due_date;
        $month = $invoice->month;
        $invoiceNo = $invoice->invoice_no;
        $status = __('Pending');

        if (getOption('send_email_status', 0) == ACTIVE) {
            $emails = [$invoice->tenant->user->email];
            $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_INVOICE)->where('status', ACTIVE)->first();
            $mailService = new MailService;
            if ($template) {
                $customizedFieldsArray = [
                    '{{amount}}' => $invoice->amount,
                    '{{due_date}}' => $invoice->due_date,
                    '{{month}}' => $invoice->month,
                    '{{invoice_no}}' => $invoice->invoice_no,
                    '{{app_name}}' => getOption('app_name')
                ];
                $content = getEmailTemplate($template->body, $customizedFieldsArray);
                $mailService->sendCustomizeMail($emails, $template->subject, $content);
            } else {
                $mailService->sendInvoiceMail($emails, $subject, $message, $ownerUserId, $title, $amount, $dueDate, $month, $invoiceNo, $status);
            }
        }

        NotificationService::send([$invoice->tenant->user_id], $subject, $message, $ownerUserId);
    }
}
