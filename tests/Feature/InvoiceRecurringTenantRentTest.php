<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceRecurringTenantRentTest extends TestCase
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

    /**
     * Also guards against the route regression found during manual testing: this endpoint
     * used to sit under auth:api (Passport) instead of the session-based owner group, so it
     * 401'd for every real browser request despite the backend logic being correct.
     */
    public function test_tenant_rent_endpoint_returns_the_units_configured_rent_not_the_tenants()
    {
        $owner = User::factory()->create(['role' => USER_ROLE_OWNER, 'status' => 1]);
        $tenantUser = User::factory()->create(['role' => USER_ROLE_TENANT, 'status' => 1, 'owner_user_id' => $owner->id]);

        $property = Property::forceCreate([
            'owner_user_id' => $owner->id,
            'property_type' => 1,
            'name' => 'Test Property',
            'number_of_unit' => 1,
        ]);

        $unit = PropertyUnit::forceCreate([
            'property_id' => $property->id,
            'unit_name' => 'Unit 1',
            'bedroom' => 1,
            'bath' => 1,
            'kitchen' => 1,
            'general_rent' => 500000,
        ]);

        Tenant::forceCreate([
            'user_id' => $tenantUser->id,
            'owner_user_id' => $owner->id,
            'job' => 'Test',
            'family_member' => 1,
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'general_rent' => 300000, // deliberately different from the unit's rent
            'status' => TENANT_STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($owner)
            ->getJson(route('owner.invoice.recurring-setting.tenant-rent', ['unitId' => $unit->id]));

        $response->assertOk();
        $this->assertEquals(500000, $response->json('data.rent'));
    }
}
