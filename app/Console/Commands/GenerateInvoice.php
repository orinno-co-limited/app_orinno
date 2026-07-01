<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceRecurringSetting;
use App\Models\Tenant;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoice by invoice recurring setting';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $invoiceRecurringSettings =  InvoiceRecurringSetting::query()
            ->with('items')
            ->where('status', ACTIVE)
            ->get();
        foreach ($invoiceRecurringSettings as $invoiceRecurring) {
            $tenant = Tenant::where('unit_id', $invoiceRecurring->property_unit_id)->where('status', TENANT_STATUS_ACTIVE)->first();
            if (!is_null($tenant)) {
                if ($invoiceRecurring->recurring_type == INVOICE_RECURRING_TYPE_MONTHLY) {
                    $invoiceExist = Invoice::query()
                        ->where('property_id', $invoiceRecurring->property_id)
                        ->where('property_unit_id', $invoiceRecurring->property_unit_id)
                        ->where('invoice_recurring_setting_id', $invoiceRecurring->id)
                        ->where('month', month(now()->format('n')))
                        ->whereYear('created_at', '=', now()->format('Y'))
                        ->exists();
                    if (!$invoiceExist) {
                        $this->generateInvoice($invoiceRecurring);
                        echo "Created \n";
                    } else {
                        echo "Already Created \n";
                    }
                } elseif ($invoiceRecurring->recurring_type == INVOICE_RECURRING_TYPE_YEARLY) {
                    $invoiceExist = Invoice::query()
                        ->where('property_id', $invoiceRecurring->property_id)
                        ->where('property_unit_id', $invoiceRecurring->property_unit_id)
                        ->where('invoice_recurring_setting_id', $invoiceRecurring->id)
                        ->whereYear('created_at', '=', now()->format('Y'))
                        ->exists();
                    if (!$invoiceExist) {
                        $this->generateInvoice($invoiceRecurring);
                        echo "Created \n";
                    } else {
                        echo "Already Created \n";
                    }
                } elseif ($invoiceRecurring->recurring_type == INVOICE_RECURRING_TYPE_CUSTOM) {
                    $invoiceExist = Invoice::query()
                        ->where('property_id', $invoiceRecurring->property_id)
                        ->where('property_unit_id', $invoiceRecurring->property_unit_id)
                        ->where('invoice_recurring_setting_id', $invoiceRecurring->id)
                        ->whereDate('created_at', '>', now()->subDays($invoiceRecurring->cycle_day))
                        ->exists();
                    if (!$invoiceExist) {
                        $this->generateInvoice($invoiceRecurring);
                        echo "Created \n";
                    } else {
                        echo "Already Created \n";
                    }
                }
            }
        }
    }

    public function generateInvoice($invoiceRecurring)
    {
        DB::beginTransaction();
        try {
            $now = now();
            $invoice = new Invoice();
            $invoice->name = $invoiceRecurring->invoice_prefix;
            $invoice->tenant_id = $invoiceRecurring->tenant_id;
            $invoice->owner_user_id = $invoiceRecurring->owner_user_id;
            $invoice->invoice_recurring_setting_id = $invoiceRecurring->id;
            $invoice->property_id = $invoiceRecurring->property_id;
            $invoice->property_unit_id = $invoiceRecurring->property_unit_id;
            $invoice->month = month($now->format('n'));
            $invoice->due_date = $now->addDays($invoiceRecurring->due_day_after)->endOfDay();
            $invoice->save();
            $totalAmount = 0;
            foreach ($invoiceRecurring->items as $item) {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->invoice_type_id = $item->invoice_type_id;
                $invoiceItem->amount = $item->amount;
                $invoiceItem->description = $item->description;
                $invoiceItem->save();
                $totalAmount += $invoiceItem->amount;
            }
            $invoice->amount = $totalAmount;
            $invoice->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
