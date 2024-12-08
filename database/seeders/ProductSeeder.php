<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = [
            ['category_id' => 2, 'name_en' => 'safeway Lucerne Medium Cheddar Cheese - 16Z', 'name_ar' => 'سيفوي لوسيرن جبنة شيدر متوسطة - 16 أونص', 'price' =>  49.95, 'photo' => '363943_1-20201101-005907-1.png', 'barcode' => '21130047659'],
            ['category_id' => 2, 'name_en' => 'Al Safi Long Life Milk - 18x100Ml
', 'name_ar' => 'الصافي حليب طويل الاجل - 18x100 مل
', 'price' =>  20.95
                , 'photo' => '323696_1-20201101-022709-1.png
', 'barcode' => '6281022140620
'],
            ['category_id' => 3, 'name_en' => 'Bigen Speedy Hair Colour Medium Brown - 1PCS
', 'name_ar' => 'بيجين صبغة الشعر السريعة للرجال بني غامق - حبة واحدة
', 'price' =>  37.50
                , 'photo' => '302198_1-20210711-080340-1.png
', 'barcode' => '4987205944598
'],
            ['category_id' => 3, 'name_en' => 'Syoss SHAMPOO ANTI-DANDRUFF PLATIN - 500 ML
', 'name_ar' => 'سيوس شامبو الشعر ضد القشرة - 500 مل
', 'price' =>  18.25
                , 'photo' => '306988_1-20201031-232757-1.png
', 'barcode' => '6192430010897
'],
            ['category_id' => 4, 'name_en' => 'Moussy Malt Beverage Pomegranate Flavor -  330 Ml
', 'name_ar' => 'موسي شراب الشعير بنكهة الرمان -  330 مل
', 'price' =>  5.00
                , 'photo' => '231356_1-20201031-231623-3.png
', 'barcode' => '76162879
'],
            ['category_id' => 4, 'name_en' => 'Fanta Strawberry Soda - 24×320Ml
', 'name_ar' => 'فانتا صودا فراولة - 24×320 مل
', 'price' =>  57.95
                , 'photo' => '378397_1-20210317-000033-1.png
', 'barcode' => '5449000299970
'],
            ['category_id' => 5, 'name_en' => 'Sanita Bouquet Household Towel -  3 Roll 67M
', 'name_ar' => 'سانيتا بوكيه ورق نشاف -  2 + 1 لفة مجاناً 67 متر
', 'price' =>  18.25
                , 'photo' => '234424_1-20210304-140647-1.png
', 'barcode' => '6281017200971
'],
            ['category_id' => 5, 'name_en' => 'Tamimi Markets Kitchen Roll - 8x3 Roll
', 'name_ar' => 'أسواق التميمي مناديل للمطبخ - 8x6 رول
', 'price' =>  89.80
                , 'photo' => '179541_1-20211012-131135-2.png
', 'barcode' => '2880001795413
'],
            ['category_id' => 6, 'name_en' => 'Luxury home Chef knife 6 inch - 1 PCS
', 'name_ar' => 'لكجري هوم سكين الطاهي 6 بوصة - قطعة واحدة
', 'price' =>  26.95
                , 'photo' => '349090_1-20201101-020950-1.png
', 'barcode' => '2800003490901
'],
            ['category_id' => 6, 'name_en' => 'Luxury home Non-stick crepe pan 24cm - 1 PCS
', 'name_ar' => 'لكجري هوم مقلاة كريب 2X24سم - قطعة واحدة
', 'price' =>  29.95
                , 'photo' => '330253_1-20201031-233911-1.png
', 'barcode' => '2800003302532
'],
            ['category_id' => 7, 'name_en' => 'Lux Soft Rose French Rose & Almond Oil Soap Bar - 75G
', 'name_ar' => 'لوكس قالب صابون الورد الناعم بالورد الفرنسي وزيت اللوز - 75 غرام
', 'price' =>  3.15
                , 'photo' => '343032_1-20201101-003838-1.png
', 'barcode' => '6281006484641
'],
            ['category_id' => 7, 'name_en' => 'Lifebuoy Lifebouy Antibacterial Hand Wash Total 10 -  500 Ml
', 'name_ar' => 'لايفبوي  صابون يد سائل مضاد للبكتيريا والعناية المتكاملة -  500 مل
', 'price' =>  32.95
                , 'photo' => '343000_1-20210805-090403-1.png
', 'barcode' => '6281006475229
'],
            ['category_id' => 8, 'name_en' => 'Sainsburys Bedtime Infusions Tea Bags - 20 count', 'name_ar' => 'سنسبري شاي بابونج بالليمون واللافندر - 20 حبة
', 'price' =>  10.95
                , 'photo' => '371133_1-20210425-100152-1.png
', 'barcode' => '305501
'],
            ['category_id' => 8, 'name_en' => 'Starbucks Espresso Roast Dark - 10×57G
', 'name_ar' => 'ستاربكس كبسولات إسبريسو محمص داكنة - 10×57 غرام
', 'price' =>  27.20
                , 'photo' => '376605_1-20210503-220555-1.png
', 'barcode' => '7613036984515
'],
            // أكمل باقي المنتجات...
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
