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
        Schema::table('property_details', function (Blueprint $table) {
            $table->decimal('lease_amount', 12, 2)->change();
        });

        Schema::table('property_units', function (Blueprint $table) {
            $table->decimal('general_rent', 12, 2)->change();
            $table->decimal('security_deposit', 12, 2)->change();
            $table->decimal('late_fee', 12, 2)->change();
            $table->decimal('incident_receipt', 12, 2)->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('total_amount', 12, 2)->change();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->decimal('general_rent', 12, 2)->change();
            $table->decimal('security_deposit', 12, 2)->change();
            $table->decimal('late_fee', 12, 2)->change();
            $table->decimal('incident_receipt', 12, 2)->change();
            $table->decimal('close_refund_amount', 12, 2)->change();
            $table->decimal('close_charge', 12, 2)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
            $table->decimal('tax_amount', 12, 2)->change();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
            $table->decimal('tax_amount', 12, 2)->change();
        });

        Schema::table('gateway_currencies', function (Blueprint $table) {
            $table->decimal('conversion_rate', 12, 2)->change();
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
        });

        Schema::table('invoice_recurring_settings', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
        });

        Schema::table('invoice_recurring_setting_items', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('monthly_price', 12, 2)->change();
            $table->decimal('yearly_price', 12, 2)->change();
        });

        Schema::table('owner_packages', function (Blueprint $table) {
            $table->decimal('monthly_price', 12, 2)->change();
            $table->decimal('yearly_price', 12, 2)->change();
        });

        Schema::table('invoice_types', function (Blueprint $table) {
            $table->decimal('tax', 12, 3)->change();
        });

        Schema::table('expense_types', function (Blueprint $table) {
            $table->decimal('tax', 12, 3)->change();
        });

        Schema::table('tax_settings', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
