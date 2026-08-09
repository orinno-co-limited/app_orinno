<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Amenities move from a free-text property_units.amenities string to a
     * proper lookup + pivot (see property_unit_amenities_plan memory), so
     * owners pick from a fixed list instead of typing. The seed list below
     * is also used to best-effort backfill whatever free text already
     * exists before that column gets dropped in the next migration.
     */
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('emoji');
            $table->timestamps();
        });

        Schema::create('property_unit_amenity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['property_unit_id', 'amenity_id']);
        });

        $amenities = [
            'WiFi / Internet' => '📶',
            'Parking' => '🚗',
            'Security Guard' => '🛡️',
            'CCTV' => '📹',
            'Backup Power / Generator' => '🔌',
            'Borehole / Water Tank' => '💧',
            'Furnished' => '🛋️',
            'Kitchen' => '🍳',
            'Swimming Pool' => '🏊',
            'Gym' => '🏋️',
            'Balcony' => '🌇',
            'Garden' => '🌳',
            'Elevator / Lift' => '🛗',
            'Air Conditioning' => '❄️',
            'Tiled Floor' => '🧱',
            'Wardrobe' => '🚪',
            'Hot Water / Geyser' => '🚿',
            'Garbage Collection' => '🗑️',
            'Pet Friendly' => '🐾',
            'Wheelchair Accessible' => '♿',
        ];

        $now = now();
        foreach ($amenities as $name => $emoji) {
            DB::table('amenities')->insert([
                'name' => $name,
                'emoji' => $emoji,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $amenityIds = DB::table('amenities')->pluck('id', 'name');
        $matchers = [
            'wifi' => 'WiFi / Internet',
            'internet' => 'WiFi / Internet',
            'parking' => 'Parking',
            'security' => 'Security Guard',
            'guard' => 'Security Guard',
            'cctv' => 'CCTV',
            'backup power' => 'Backup Power / Generator',
            'power backup' => 'Backup Power / Generator',
            'generator' => 'Backup Power / Generator',
            'borehole' => 'Borehole / Water Tank',
            'water' => 'Borehole / Water Tank',
            'furnished' => 'Furnished',
            'kitchen' => 'Kitchen',
            'pool' => 'Swimming Pool',
            'gym' => 'Gym',
            'balcony' => 'Balcony',
            'garden' => 'Garden',
            'elevator' => 'Elevator / Lift',
            'lift' => 'Elevator / Lift',
            'air condition' => 'Air Conditioning',
            'tiled' => 'Tiled Floor',
            'wardrobe' => 'Wardrobe',
            'hot water' => 'Hot Water / Geyser',
            'geyser' => 'Hot Water / Geyser',
            'garbage' => 'Garbage Collection',
            'pet' => 'Pet Friendly',
            'wheelchair' => 'Wheelchair Accessible',
        ];

        $units = DB::table('property_units')->whereNotNull('amenities')->where('amenities', '!=', '')->get(['id', 'amenities']);
        $pivotRows = [];
        foreach ($units as $unit) {
            $matchedNames = [];
            foreach (explode(',', $unit->amenities) as $piece) {
                $piece = strtolower(trim($piece));
                if ($piece === '') {
                    continue;
                }
                foreach ($matchers as $needle => $amenityName) {
                    if (str_contains($piece, $needle)) {
                        $matchedNames[$amenityName] = true;
                        break;
                    }
                }
            }
            foreach (array_keys($matchedNames) as $amenityName) {
                if (isset($amenityIds[$amenityName])) {
                    $pivotRows[] = [
                        'property_unit_id' => $unit->id,
                        'amenity_id' => $amenityIds[$amenityName],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        if (!empty($pivotRows)) {
            DB::table('property_unit_amenity')->insert($pivotRows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('property_unit_amenity');
        Schema::dropIfExists('amenities');
    }
};
