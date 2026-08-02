<?php

namespace Database\Seeders;

use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $yangon = ShippingZone::create([
            'name' => 'Yangon',
            'fee' => 2.00,
            'estimated_days' => '1-2 days',
            'description' => 'Yangon Region',
        ]);

        $central = ShippingZone::create([
            'name' => 'Central Myanmar',
            'fee' => 4.00,
            'estimated_days' => '2-4 days',
            'description' => 'Mandalay, Bago, Ayeyarwady, Magway, Sagaing, Naypyidaw',
        ]);

        $remote = ShippingZone::create([
            'name' => 'Other States',
            'fee' => 7.00,
            'estimated_days' => '4-7 days',
            'description' => 'Chin, Kachin, Kayah, Kayin, Mon, Rakhine, Shan, Tanintharyi',
        ]);

        $regions = [
            $yangon->id => ['Yangon'],
            $central->id => ['Mandalay', 'Bago', 'Ayeyarwady', 'Magway', 'Sagaing', 'Naypyidaw'],
            $remote->id => ['Chin', 'Kachin', 'Kayah', 'Kayin', 'Mon', 'Rakhine', 'Shan', 'Tanintharyi'],
        ];

        foreach ($regions as $zoneId => $stateNames) {
            foreach ($stateNames as $state) {
                ShippingLocation::create([
                    'shipping_zone_id' => $zoneId,
                    'country' => 'Myanmar',
                    'state_region' => $state,
                    'city' => 'All',
                    'district_area' => null,
                ]);
            }
        }
    }
}
