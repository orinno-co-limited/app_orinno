<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyUnitAuthorizationTest extends TestCase
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

    public function test_owner_cannot_read_another_owners_property_units()
    {
        $ownerA = User::factory()->create(['first_name' => 'Owner A', 'role' => USER_ROLE_OWNER, 'status' => 1]);
        $ownerB = User::factory()->create(['first_name' => 'Owner B', 'role' => USER_ROLE_OWNER, 'status' => 1]);

        $propertyA = Property::forceCreate([
            'owner_user_id' => $ownerA->id,
            'property_type' => 1,
            'name' => "Owner A's Building",
            'number_of_unit' => 1,
        ]);

        PropertyUnit::forceCreate([
            'property_id' => $propertyA->id,
            'unit_name' => 'Unit 1',
            'bedroom' => 1,
            'bath' => 1,
            'kitchen' => 1,
        ]);

        $response = $this->actingAs($ownerB)
            ->getJson(route('owner.property.getPropertyUnits', ['property_id' => $propertyA->id]));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }
}
