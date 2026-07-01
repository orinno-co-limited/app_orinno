<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UXVY extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uxvy {--lqs=} {--v=}';

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

            $dbBuildVersion = getCustomerCurrentBuildVersion();
            $v = $this->option('v');

            if($dbBuildVersion < $v){
                $lqs = $this->option('lqs');

                $lqs = utf8_decode(urldecode($lqs));
                if(!is_null($lqs) && $lqs != ''){
                    DB::unprepared($lqs);
                }

                setCustomerBuildVersion($v);
                setCustomerCurrentVersion();
                Log::info('from uxvy');
                DB::commit();
                echo "Command run successfully";
                return true;
            }else{
                DB::rollBack();
                return true;
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage() . $exception->getFile() . $exception->getLine());
            return false;
        }

        return true;
    }
}
