<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // CommonMiddleware redirects every request to /install unless this marker exists,
        // and separately requires at least one Language row to resolve the app locale.
        file_put_contents(storage_path('installed'), '1');
        Language::create(['name' => 'English', 'code' => 'en', 'default' => 1, 'status' => 1]);
    }

    protected function tearDown(): void
    {
        @unlink(storage_path('installed'));
        parent::tearDown();
    }

    private function makeOwner(string $firstName): User
    {
        return User::factory()->create(['first_name' => $firstName, 'role' => USER_ROLE_OWNER, 'status' => 1]);
    }

    public function test_owner_cannot_read_another_owners_role()
    {
        $ownerA = $this->makeOwner('Owner A');
        $ownerB = $this->makeOwner('Owner B');

        $roleA = Role::create([
            'name' => 'manager-' . $ownerA->id,
            'display_name' => 'Manager',
            'guard_name' => 'web',
            'user_id' => $ownerA->id,
            'status' => 1,
        ]);

        $response = $this->actingAs($ownerB)
            ->getJson(route('owner.role-permission.get-info', ['id' => $roleA->id]));

        $response->assertStatus(404);
    }

    public function test_owner_cannot_delete_another_owners_role()
    {
        $ownerA = $this->makeOwner('Owner A');
        $ownerB = $this->makeOwner('Owner B');

        $roleA = Role::create([
            'name' => 'manager-' . $ownerA->id,
            'display_name' => 'Manager',
            'guard_name' => 'web',
            'user_id' => $ownerA->id,
            'status' => 1,
        ]);

        $this->actingAs($ownerB)
            ->getJson(route('owner.role-permission.delete', ['id' => $roleA->id]));

        $this->assertDatabaseHas('roles', ['id' => $roleA->id]);
    }
}
