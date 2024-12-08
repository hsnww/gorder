<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name_en' => 'FROZEN', 'name_ar' => 'المأكولات المجمدة'],
            ['name_en' => 'DAIRY', 'name_ar' => 'الالبان'],
            ['name_en' => 'HAIR CARE', 'name_ar' => 'العناية بالشعر'],
            ['name_en' => 'WATER & BEVERAGES', 'name_ar' => 'المياه والمشروبات'],
            ['name_en' => 'DISPOSABLES', 'name_ar' => 'إستهلاك يومي'],
            ['name_en' => 'KITCHEN SHOP', 'name_ar' => 'أدوات المطبخ'],
            ['name_en' => 'SOAPS', 'name_ar' => 'صابون'],
            ['name_en' => 'HOT DRINKS & CREAMERS', 'name_ar' => 'مشروبات ساخنة ومبيض القهوة'],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(['name_en' => $category['name_en']], $category);
        }
    }
}
