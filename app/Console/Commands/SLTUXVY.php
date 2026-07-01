<?php

namespace App\Console\Commands;

use App\Http\Controllers\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SLTUXVY extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sltuxvy {--lqs=} {--v=}';

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
        DB::beginTransaction();
        try {
            $logger = new Logger(storage_path('logs/addon.log'));
            $dbBuildVersion = getCustomerAddonBuildVersion('PROTYLISTING');
            $v = $this->option('v');

            $logger->log('Command sltuxvy', '===========START==========');
            $logger->log('Version Checking', 'START');
            $logger->log('Addon DB Build Version', $dbBuildVersion);
            $logger->log('Command build version', $v);
            if ($dbBuildVersion < $v) {
                $logger->log('Version Checking', 'True');
                $logger->log('Version Checking', 'END');
                $lqs = $this->option('lqs');
                $logger->log('LQS', $lqs);

                $lqs = utf8_decode(urldecode($lqs));
                if (!is_null($lqs) && $lqs != '') {
                    DB::unprepared($lqs);
                }
                setCustomerAddonBuildVersion('PROTYLISTING', $v);
                setCustomerAddonCurrentVersion('PROTYLISTING');
                DB::commit();
                $logger->log('Command RUN', 'DONE');
                $logger->log('Command sltuxvy', '===========END==========');
                return true;
            } else {
                DB::rollBack();
                $logger->log('Version Checking', 'FALSe');
                $logger->log('Version Checking', 'END');
                $logger->log('Command sltuxvy', '===========END==========');
                return true;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $logger->log('Exception', $exception->getMessage() . $exception->getFile() . $exception->getLine());
            $logger->log('Command sltuxvy', '===========END==========');
            return false;
        }

        return true;
    }
}
