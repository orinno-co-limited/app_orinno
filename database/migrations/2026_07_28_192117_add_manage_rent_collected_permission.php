<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Adds a dedicated "Manage Rent Collected" permission so an owner can
     * choose which staff/team-member roles are allowed to see rent-collected
     * figures, instead of every staff account seeing them by default.
     */
    public function up()
    {
        $exists = DB::table('permissions')
            ->where('name', 'Manage Rent Collected')
            ->where('guard_name', 'web')
            ->exists();

        if (!$exists) {
            DB::table('permissions')->insert([
                'name' => 'Manage Rent Collected',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Spatie caches the permission list for performance; inserting a row
        // directly via the query builder (rather than Permission::create())
        // doesn't fire the event that normally clears it, so a role update
        // right after this migration would fail with "permission does not
        // exist" until the cache happened to expire on its own.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down()
    {
        DB::table('permissions')
            ->where('name', 'Manage Rent Collected')
            ->where('guard_name', 'web')
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
