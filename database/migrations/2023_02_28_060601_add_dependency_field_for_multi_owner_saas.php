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
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('id')->nullable();
        });
        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('user_id')->nullable();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('tenant_id')->nullable();
        });
        Schema::table('invoice_recurring_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('tenant_id')->nullable();
        });
        Schema::table('invoice_types', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('id')->nullable();
            $table->dropUnique(['name']);
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('name')->nullable();
        });
        Schema::table('expense_types', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('name')->nullable();
            $table->dropUnique(['name']);
        });
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('tenant_id')->nullable();
        });
        Schema::table('kyc_configs', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('tenant_id')->nullable();
        });
        Schema::table('information', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('property_id')->nullable();
        });
        Schema::table('maintenance_issues', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('name')->nullable();
        });
        Schema::table('maintainers', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('property_id')->nullable();
        });
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('property_id')->nullable();
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('property_id')->nullable();
        });
        Schema::table('ticket_topics', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('name')->nullable();
            $table->dropUnique(['name']);
        });
        Schema::table('notice_boards', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('title')->nullable();
        });
        Schema::table('gateways', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unsignedBigInteger('owner_user_id')->after('id')->nullable();
        });
        Schema::table('gateway_currencies', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('gateway_id')->nullable();
        });
        Schema::table('banks', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_user_id')->after('gateway_id')->nullable();
            $table->tinyText('details')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('invoice_recurring_settings', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('invoice_types', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('expense_types', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('kyc_configs', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('maintenance_issues', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('maintainers', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('ticket_topics', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('notice_boards', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('gateways', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('gateway_currencies', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('owner_user_id');
        });
    }
};
