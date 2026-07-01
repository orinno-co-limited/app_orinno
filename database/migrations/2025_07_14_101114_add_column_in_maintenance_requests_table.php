<?php

use App\Models\MaintenanceRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->string('request_id')->nullable()->after('id');
            $table->unsignedBigInteger('created_by')->nullable()->after('id');
        });

        MaintenanceRequest::withoutTrashed()->chunk(100, function ($requests) {
            foreach ($requests as $request) {
                $request->request_id = 'M' . str_pad($request->id, 7, '0', STR_PAD_LEFT);
                $request->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropColumn('request_id');
            $table->dropColumn('created_by');
        });
    }
};
