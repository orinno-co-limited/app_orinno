<?php

namespace App\Console\Commands;

use App\Http\Controllers\Logger;
use App\Models\Bank;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Information;
use App\Models\Invoice;
use App\Models\InvoiceRecurringSetting;
use App\Models\InvoiceType;
use App\Models\KycConfig;
use App\Models\KycVerification;
use App\Models\Maintainer;
use App\Models\MaintenanceIssue;
use App\Models\MaintenanceRequest;
use App\Models\NoticeBoard;
use App\Models\Owner;
use App\Models\Property;
use App\Models\TaxSetting;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\TicketTopic;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SU1V1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'su1v1 {--lqs=} {--v=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logger = new Logger(storage_path('logs/addon.log'));
        $logger->log('Command su1v1', '===========START==========');
        try {

            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            $dbAddonBuildVersion = getCustomerAddonBuildVersion('PROTYSAAS');
            $dbBuildVersion = getCustomerCurrentBuildVersion();
            $logger->log('Checking version', 'Start');
            if ($dbAddonBuildVersion == 0 && $dbBuildVersion == 4) {
                $logger->log('Check status', 'true');
                $logger->log('Checking version', 'END');

                $logger->log('DB backup', 'START');
                try {
                    // lock all tables
                    DB::unprepared('FLUSH TABLES WITH READ LOCK;');

                    // run the artisan command to backup the db using the package I linked to
                    Artisan::call('backup:run', ['--only-db' => true]);  // something like this

                    // unlock all tables
                    DB::unprepared('UNLOCK TABLES');
                    $logger->log('DB backup', 'DONE');
                } catch (\Exception $e) {
                    $logger->log('DB backup', 'FAILED');
                    DB::unprepared('UNLOCK TABLES');
                }

                DB::beginTransaction();

                $lqs = $this->option('lqs');
                $logger->log('LQS name', $lqs);
                $logger->log('LQS check', 'START');
                $lqs = utf8_decode(urldecode($lqs));
                if (!is_null($lqs) && $lqs != '') {
                    $logger->log('LQS check', 'True');
                    $logger->log('LQS RUN', 'START');
                    DB::unprepared($lqs);
                    $logger->log('LQS RUN', 'DONE');
                } else {
                    $logger->log('LQS check', 'False');
                }

                $logger->log('Custom migration', 'START');
                //Migrate owners user
                $ownerUser = User::where('role', 1)->first();
                Owner::create(['user_id' => $ownerUser->id]);

                $updateData = ['owner_user_id' => $ownerUser->id];
                //Migrate owners user id in user table
                User::whereNotIn('role', [4, 1])->update($updateData);

                //Migrate all dependencies of owner_id to all table
                Property::where('id', '!=', 0)->update($updateData);
                Tenant::where('id', '!=', 0)->update($updateData);
                Invoice::where('id', '!=', 0)->update($updateData);
                InvoiceRecurringSetting::where('id', '!=', 0)->update($updateData);
                InvoiceType::where('id', '!=', 0)->update($updateData);
                Expense::where('id', '!=', 0)->update($updateData);
                ExpenseType::where('id', '!=', 0)->update($updateData);
                KycConfig::where('id', '!=', 0)->update($updateData);
                KycVerification::where('id', '!=', 0)->update($updateData);
                Information::where('id', '!=', 0)->update($updateData);
                Maintainer::where('id', '!=', 0)->update($updateData);
                MaintenanceIssue::where('id', '!=', 0)->update($updateData);
                MaintenanceRequest::where('id', '!=', 0)->update($updateData);
                Ticket::where('id', '!=', 0)->update($updateData);
                TicketTopic::where('id', '!=', 0)->update($updateData);
                NoticeBoard::where('id', '!=', 0)->update($updateData);
                Gateway::where('id', '!=', 0)->update($updateData);
                GatewayCurrency::where('id', '!=', 0)->update($updateData);
                Bank::where('id', '!=', 0)->update($updateData);

                //Migrate old tax setting to tax-settings table
                TaxSetting::create(['owner_user_id' => $ownerUser->id, 'type' => getOption('tax_type')]);
                $logger->log('Custom migration', 'DONE');

                $logger->log('Set build and current version', 'START');
                setCustomerAddonBuildVersion('PROTYSAAS', 1);
                setCustomerAddonCurrentVersion('PROTYSAAS');
                $logger->log('Set build and current version', 'DONE');
            }

            $logger->log('Check status', 'false');
            $logger->log('Checking version', 'END');

            DB::commit();
            // echo "Command run successfully";
            $logger->log('Command su1v1', '===========END==========');
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            $logger->log('Exception', $exception->getMessage() . $exception->getFile() . $exception->getLine());
            $logger->log('Command su1v1', '===========END==========');
            return false;
        }
    }
}
