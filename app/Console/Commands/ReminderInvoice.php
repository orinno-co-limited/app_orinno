<?php

namespace App\Console\Commands;

use App\Models\EmailTemplate;
use App\Models\Invoice;
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
            if (getOption('remainder_status', 0) == REMAINDER_STATUS_ACTIVE) {
                $mailService = new MailService;
                $invoices = Invoice::query()
                    ->where('status', INVOICE_STATUS_PENDING)
                    ->get();

                if (getOption('remainder_everyday_status') == REMAINDER_EVERYDAY_STATUS_ACTIVE) {
                    foreach ($invoices as $invoice) {
                        if ($invoice->due_date >= today()) {
                            if (getOption('send_email_status', 0) == ACTIVE) {
                                $emails = [$invoice->tenant->user->email];
                                $subject =  __('Payment reminder') . ' ' . $invoice->invoice_no . ' ' . __('due on date') . ' '  . $invoice->due_date;
                                $title =  __('Payment reminder!');
                                $message = __('You have a due invoice');
                                $ownerUserId = $invoice->owner_user_id;
                                $amount = $invoice->amount;
                                $dueDate = $invoice->due_date;
                                $month = $invoice->month;
                                $invoiceNo = $invoice->invoice_no;
                                $status = __('Pending');

                                // send mail
                                $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_INVOICE)->where('status', ACTIVE)->first();
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
                        }
                    }
                }

                $reminderDaysArray = explode(',', getOption('reminder_days'));
                if (getOption('remainder_everyday_status') != REMAINDER_EVERYDAY_STATUS_ACTIVE) {
                    foreach ($invoices as $invoice) {
                        if ($invoice->due_date >= today()) {
                            $from_date = Carbon::parse($invoice->due_date);
                            $diffDay = $from_date->diffInDays(today());
                            if (in_array($diffDay, $reminderDaysArray)) {
                                if (getOption('send_email_status', 0) == ACTIVE) {
                                    $emails = [$invoice->tenant->user->email];
                                    $subject =  __('Payment reminder') . ' ' . $invoice->invoice_no . ' ' . __('due on date') . ' '  . $invoice->due_date;
                                    $title =  __('Payment reminder!');
                                    $message = __('You have a due invoice');
                                    $ownerUserId = $invoice->owner_user_id;
                                    $amount = $invoice->amount;
                                    $dueDate = $invoice->due_date;
                                    $month = $invoice->month;
                                    $invoiceNo = $invoice->invoice_no;
                                    $status = __('Pending');

                                    // send mail
                                    $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_INVOICE)->where('status', ACTIVE)->first();
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
                            }
                        }
                    }
                }
            } else {
                throw new Exception('reminder status inactive');
            }
        } catch (Exception $e) {
            Log::info('auto reminder error: ' . $e->getMessage());
        }
    }
}
