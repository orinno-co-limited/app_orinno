<?php

namespace Tests\Feature;

use App\Models\InvoiceRecurringSetting;
use App\Models\InvoiceRecurringSettingItem;
use App\Models\Language;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateInvoiceCommandTest extends TestCase
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

    private function makeRecurringSetting(?int $generationDay): InvoiceRecurringSetting
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

        $tenant = Tenant::forceCreate([
            'user_id' => $tenantUser->id,
            'owner_user_id' => $owner->id,
            'job' => 'Test',
            'family_member' => 1,
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'status' => TENANT_STATUS_ACTIVE,
        ]);

        $setting = InvoiceRecurringSetting::forceCreate([
            'tenant_id' => $tenant->id,
            'owner_user_id' => $owner->id,
            'property_id' => $property->id,
            'property_unit_id' => $unit->id,
            'recurring_type' => INVOICE_RECURRING_TYPE_MONTHLY,
            'generation_day' => $generationDay,
            'due_day_after' => 5,
            'invoice_prefix' => 'INV',
            'status' => ACTIVE,
        ]);

        InvoiceRecurringSettingItem::forceCreate([
            'invoice_recurring_setting_id' => $setting->id,
            'invoice_type_id' => 1,
            'amount' => 500000,
            'description' => 'Rent',
        ]);

        return $setting;
    }

    public function test_it_does_not_generate_before_the_configured_generation_day()
    {
        $this->travelTo(Carbon::create(2026, 6, 15));
        $this->makeRecurringSetting(20);

        $this->artisan('generate:invoice');

        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_it_generates_on_the_configured_generation_day()
    {
        $this->travelTo(Carbon::create(2026, 6, 15));
        $this->makeRecurringSetting(15);

        $this->artisan('generate:invoice');

        $this->assertDatabaseCount('invoices', 1);
    }

    public function test_it_generates_after_the_configured_generation_day_too()
    {
        $this->travelTo(Carbon::create(2026, 6, 15));
        $this->makeRecurringSetting(10);

        $this->artisan('generate:invoice');

        $this->assertDatabaseCount('invoices', 1);
    }

    public function test_it_generates_immediately_when_generation_day_is_not_set()
    {
        $this->travelTo(Carbon::create(2026, 6, 15));
        $this->makeRecurringSetting(null);

        $this->artisan('generate:invoice');

        $this->assertDatabaseCount('invoices', 1);
    }

    public function test_it_does_not_generate_twice_in_the_same_month()
    {
        $this->travelTo(Carbon::create(2026, 6, 15));
        $this->makeRecurringSetting(15);

        $this->artisan('generate:invoice');
        $this->artisan('generate:invoice');

        $this->assertDatabaseCount('invoices', 1);
    }
}
