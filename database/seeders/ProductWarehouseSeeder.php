<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductWarehouseSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 192; $i++) {
            $data[] = [
                'product_id' => rand(1, 16), // اختيار عشوائي بين 1 و 16
                'provider_id' => rand(1, 12), // اختيار عشوائي بين 1 و 12
                'price' => rand(800, 9400) / 100, // سعر عشوائي بين 8.35 و 94.00
                'quantity' => rand(50, 150), // كمية عشوائية بين 50 و 150
                'status' => rand(1, 10) > 2 ? 'active' : 'pending', // "active" للغالبية و "pending" لنسبة قليلة
                'created_at' => now(), // تاريخ حديث
                'updated_at' => now(), // تاريخ حديث
            ];
        }

        DB::table('product_warehouses')->insert($data);
    }
}
