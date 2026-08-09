<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Language;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReminderInvoiceCommandTest extends TestCase
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
     * getOption() reads from config('settings'), populated once during AppServiceProvider::boot()
     * - which already ran by the time a test body executes. Refresh it after seeding so getOption()
     * picks up what the test just wrote instead of what existed at boot time.
     */
    private function seedSettings(array $options): void
    {
        foreach ($options as $key => $value) {
            Setting::updateOrCreate(['option_key' => $key], ['option_value' => $value]);
        }
        config(['settings' => Setting::all()->pluck('option_value', 'option_key')->toArray()]);
    }

    private function makeInvoice(string $dueDate, int $reminderCount = 0): Invoice
    {
        $owner = User::factory()->create(['role' => USER_ROLE_OWNER, 'status' => 1]);
        $tenantUser = User::factory()->create([
            'role' => USER_ROLE_TENANT,
            'status' => 1,
            'owner_user_id' => $owner->id,
            'contact_number' => '+256700000000',
            'notify_whatsapp' => true,
        ]);

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

        return Invoice::forceCreate([
            'tenant_id' => $tenant->id,
            'owner_user_id' => $owner->id,
            'property_id' => $property->id,
            'property_unit_id' => $unit->id,
            'name' => 'INV',
            'month' => 'June',
            'due_date' => $dueDate,
            'amount' => 500000,
            'status' => INVOICE_STATUS_PENDING,
            'reminder_count' => $reminderCount,
        ]);
    }

    public function test_it_does_not_remind_invoices_that_are_not_yet_due()
    {
        $this->seedSettings([
            'remainder_status' => '1',
            'remainder_everyday_status' => '1',
            'reminder_max_count' => '3',
        ]);
        $invoice = $this->makeInvoice(now()->addDays(5)->toDateString());

        $this->artisan('reminder:invoice');

        $this->assertEquals(0, $invoice->fresh()->reminder_count);
    }

    public function test_it_reminds_overdue_invoices_via_whatsapp_and_increments_count()
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.test']]], 200),
        ]);
        $this->seedSettings([
            'remainder_status' => '1',
            'remainder_everyday_status' => '1',
            'reminder_max_count' => '3',
            'WHATSAPP_STATUS' => '1',
            'META_WHATSAPP_ACCESS_TOKEN' => 'fake-token',
            'META_WHATSAPP_PHONE_NUMBER_ID' => 'fake-phone-id',
        ]);
        $invoice = $this->makeInvoice(now()->subDays(3)->toDateString());

        $this->artisan('reminder:invoice');

        $fresh = $invoice->fresh();
        $this->assertEquals(1, $fresh->reminder_count);
        $this->assertEquals(INVOICE_STATUS_PENDING, $fresh->status);
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'graph.facebook.com');
        });
    }

    public function test_it_flags_overdue_and_stops_reminding_after_max_reached()
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.test']]], 200),
        ]);
        $this->seedSettings([
            'remainder_status' => '1',
            'remainder_everyday_status' => '1',
            'reminder_max_count' => '2',
            'WHATSAPP_STATUS' => '1',
            'META_WHATSAPP_ACCESS_TOKEN' => 'fake-token',
            'META_WHATSAPP_PHONE_NUMBER_ID' => 'fake-phone-id',
        ]);
        $invoice = $this->makeInvoice(now()->subDays(10)->toDateString(), reminderCount: 2);

        $this->artisan('reminder:invoice');

        $fresh = $invoice->fresh();
        $this->assertEquals(INVOICE_STATUS_OVER_DUE, $fresh->status);
        $this->assertEquals(2, $fresh->reminder_count);
        Http::assertNotSent(function ($request) {
            return str_contains($request->url(), 'graph.facebook.com');
        });
    }
}
