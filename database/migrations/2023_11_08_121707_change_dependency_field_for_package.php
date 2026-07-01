<?php

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
        Schema::table('packages', function (Blueprint $table) {
            $table->tinyInteger('type')->default(PACKAGE_TYPE_DEFAULT)->after('yearly_price');
            $table->decimal('per_monthly_price', 12, 2)->default(0)->after('yearly_price');
            $table->decimal('per_yearly_price', 12, 2)->default(0)->after('yearly_price');
        });

        Schema::table('subscription_orders', function (Blueprint $table) {
            $table->tinyInteger('package_type')->default(PACKAGE_TYPE_DEFAULT);
            $table->integer('quantity')->default(1);
        });

        Schema::table('owner_packages', function (Blueprint $table) {
            $table->tinyInteger('package_type')->default(PACKAGE_TYPE_DEFAULT);
            $table->integer('quantity')->default(1);
            $table->decimal('per_monthly_price', 12, 2)->default(0)->after('yearly_price');
            $table->decimal('per_yearly_price', 12, 2)->default(0)->after('yearly_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('per_monthly_price');
            $table->dropColumn('per_yearly_price');
        });

        Schema::table('subscription_orders', function (Blueprint $table) {
            $table->dropColumn('package_type');
            $table->dropColumn('quantity');
        });

        Schema::table('owner_packages', function (Blueprint $table) {
            $table->dropColumn('package_type');
            $table->dropColumn('quantity');
            $table->dropColumn('per_monthly_price');
            $table->dropColumn('per_yearly_price');
        });
    }
};
