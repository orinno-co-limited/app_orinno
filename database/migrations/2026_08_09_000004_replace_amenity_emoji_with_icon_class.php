<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Amenities were briefly emoji-labelled (📶, 🚗, ...); switched to the
     * self-hosted Remix Icon font already used everywhere else in the app
     * instead — no external font/network dependency, consistent with the
     * rest of the UI, and renders reliably across platforms unlike emoji
     * glyphs which vary by OS/browser.
     */
    public function up(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->renameColumn('emoji', 'icon');
        });

        $iconByName = [
            'WiFi / Internet' => 'ri-wifi-line',
            'Parking' => 'ri-parking-box-line',
            'Security Guard' => 'ri-shield-line',
            'CCTV' => 'ri-camera-line',
            'Backup Power / Generator' => 'ri-flashlight-line',
            'Borehole / Water Tank' => 'ri-water-flash-line',
            'Furnished' => 'ri-hotel-bed-line',
            'Kitchen' => 'ri-fridge-line',
            'Swimming Pool' => 'ri-drop-line',
            'Gym' => 'ri-boxing-line',
            'Balcony' => 'ri-window-line',
            'Garden' => 'ri-plant-line',
            'Elevator / Lift' => 'ri-arrow-up-down-line',
            'Air Conditioning' => 'ri-snowy-line',
            'Tiled Floor' => 'ri-layout-grid-line',
            'Wardrobe' => 'ri-shirt-line',
            'Hot Water / Geyser' => 'ri-showers-line',
            'Garbage Collection' => 'ri-delete-bin-line',
            'Pet Friendly' => 'ri-footprint-line',
            'Wheelchair Accessible' => 'ri-wheelchair-line',
        ];

        foreach ($iconByName as $name => $icon) {
            DB::table('amenities')->where('name', $name)->update(['icon' => $icon]);
        }
    }

    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->renameColumn('icon', 'emoji');
        });
    }
};
