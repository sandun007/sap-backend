<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Category;
use Carbon\Carbon;

class ERPSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Branches
        $branches = [
            [
                'branch_code' => 'DX/BRN/0001',
                'branch_name' => 'Main Branch',
                'address' => '123 Galle Road, Colombo 03',
                'phone_num1' => '011-2345678',
                'phone_num2' => '011-2345679',
                'email' => 'main@isuru.com',
                'manager_id' => null,
                'opening_date' => '2020-01-01',
                'is_active' => 1,
            ],
            [
                'branch_code' => 'DX/BRN/0002',
                'branch_name' => 'Colombo Branch',
                'address' => '45 Duplication Road, Colombo 04',
                'phone_num1' => '011-2445678',
                'phone_num2' => null,
                'email' => 'colombo@isuru.com',
                'manager_id' => null,
                'opening_date' => '2022-06-15',
                'is_active' => 1,
            ],
            [
                'branch_code' => 'DX/BRN/0003',
                'branch_name' => 'Negombo Branch',
                'address' => '89 Main Street, Negombo',
                'phone_num1' => '031-2245678',
                'phone_num2' => null,
                'email' => 'negombo@isuru.com',
                'manager_id' => null,
                'opening_date' => '2024-03-10',
                'is_active' => 1,
            ]
        ];

        $branchIds = [];
        foreach ($branches as $b) {
            $branch = Branch::updateOrCreate(['branch_code' => $b['branch_code']], $b);
            $branchIds[] = $branch->branch_id;
        }

        // 2. Seed Brands
        $brands = [
            ['brand_code' => 'DX/BRAND/0001', 'brand_name' => 'Nestle'],
            ['brand_code' => 'DX/BRAND/0002', 'brand_name' => 'Unilever'],
            ['brand_code' => 'DX/BRAND/0003', 'brand_name' => 'P&G'],
            ['brand_code' => 'DX/BRAND/0004', 'brand_name' => 'Anchor'],
            ['brand_code' => 'DX/BRAND/0005', 'brand_name' => 'Keells'],
            ['brand_code' => 'DX/BRAND/0006', 'brand_name' => 'Maliban'],
            ['brand_code' => 'DX/BRAND/0007', 'brand_name' => 'Munchee'],
            ['brand_code' => 'DX/BRAND/0008', 'brand_name' => 'Elephant House'],
            ['brand_code' => 'DX/BRAND/0009', 'brand_name' => 'Coca-Cola Company'],
            ['brand_code' => 'DX/BRAND/0010', 'brand_name' => 'Prima'],
        ];

        $brandIds = [];
        foreach ($brands as $br) {
            $brand = Brand::updateOrCreate(['brand_code' => $br['brand_code']], $br);
            $brandIds[$br['brand_name']] = $brand->brand_id;
        }

        // 3. Seed Suppliers
        $suppliers = [
            ['supplier_id' => 'DX/SUP/0001', 'supplier_name' => 'Ceylon Distributors', 'contact_person' => 'Kamal Perera', 'phone' => '077-1234567', 'email' => 'kamal@ceylondist.com', 'address' => 'Colombo 10', 'tax_id' => 'TX-1002', 'payment_terms' => '30 Days'],
            ['supplier_id' => 'DX/SUP/0002', 'supplier_name' => 'Lanka Foods', 'contact_person' => 'Nimal Silva', 'phone' => '077-7654321', 'email' => 'nimal@lankafoods.com', 'address' => 'Kandy Road, Kelaniya', 'tax_id' => 'TX-2004', 'payment_terms' => '15 Days'],
            ['supplier_id' => 'DX/SUP/0003', 'supplier_name' => 'United Wholesalers', 'contact_person' => 'John Doe', 'phone' => '071-2233445', 'email' => 'info@unitedwholesalers.com', 'address' => 'Orugodawatta', 'tax_id' => 'TX-3006', 'payment_terms' => 'Cash on Delivery'],
            ['supplier_id' => 'DX/SUP/0004', 'supplier_name' => 'Metro Distributing', 'contact_person' => 'Sahan Fernando', 'phone' => '075-8899001', 'email' => 'sahan@metrodist.com', 'address' => 'Wattala', 'tax_id' => 'TX-4008', 'payment_terms' => '45 Days'],
            ['supplier_id' => 'DX/SUP/0005', 'supplier_name' => 'Alpha Suppliers', 'contact_person' => 'Ravi Kumar', 'phone' => '072-5566778', 'email' => 'ravi@alphasuppliers.com', 'address' => 'Pettah, Colombo 11', 'tax_id' => 'TX-5010', 'payment_terms' => '30 Days'],
        ];

        $supplierIds = [];
        foreach ($suppliers as $s) {
            $supplier = Supplier::updateOrCreate(['supplier_id' => $s['supplier_id']], $s);
            $supplierIds[] = $supplier->supplier_id;
        }

        // 4. Map user-created categories to subcategories
        $categoryMap = Category::pluck('category_id', 'category_name')->toArray();

        // Subcategory definitions mapped to category names
        $subcategoriesToSeed = [
            'Fresh Produce' => [
                ['code' => 'DX/SUB/0001', 'name' => 'Fruits', 'desc' => 'Fresh seasonal fruits'],
                ['code' => 'DX/SUB/0002', 'name' => 'Vegetables', 'desc' => 'Fresh organic vegetables'],
            ],
            'Bakery' => [
                ['code' => 'DX/SUB/0003', 'name' => 'Bread & Buns', 'desc' => 'Breads, burger buns, rolls'],
                ['code' => 'DX/SUB/0004', 'name' => 'Cakes & Pastries', 'desc' => 'Sweet pastries, cupcakes, fresh cakes'],
            ],
            'Dairy & Eggs' => [
                ['code' => 'DX/SUB/0005', 'name' => 'Milk & Yogurt', 'desc' => 'Fresh milk, low-fat milk, set yogurts'],
                ['code' => 'DX/SUB/0006', 'name' => 'Cheese & Butter', 'desc' => 'Salted butter, sliced and block cheeses'],
                ['code' => 'DX/SUB/0007', 'name' => 'Eggs', 'desc' => 'Poultry and farm fresh eggs'],
            ],
            'Meat & Seafood' => [
                ['code' => 'DX/SUB/0008', 'name' => 'Poultry', 'desc' => 'Chicken breast, thighs, drumsticks'],
                ['code' => 'DX/SUB/0009', 'name' => 'Beef & Mutton', 'desc' => 'Premium beef cuts and mutton curry cuts'],
                ['code' => 'DX/SUB/0010', 'name' => 'Seafood', 'desc' => 'Salmon, tuna, and fresh sea crabs'],
            ],
            'Frozen Foods' => [
                ['code' => 'DX/SUB/0011', 'name' => 'Frozen Snacks', 'desc' => 'Frozen pizzas, nuggets, fries'],
                ['code' => 'DX/SUB/0012', 'name' => 'Ice Cream', 'desc' => 'Dessert tubs and popsicles'],
            ],
            'Beverages' => [
                ['code' => 'DX/SUB/0013', 'name' => 'Soft Drinks', 'desc' => 'Carbonated fizzy beverages'],
                ['code' => 'DX/SUB/0014', 'name' => 'Juices & Water', 'desc' => 'Bottled pure water and fruit juices'],
                ['code' => 'DX/SUB/0015', 'name' => 'Tea & Coffee', 'desc' => 'Green tea bags and ground coffee packs'],
            ],
            'Snacks & Confectionery' => [
                ['code' => 'DX/SUB/0016', 'name' => 'Chips & Crackers', 'desc' => 'Potato chips, crackers, and nuts'],
                ['code' => 'DX/SUB/0017', 'name' => 'Chocolates & Sweets', 'desc' => 'Milk chocolates and gummy candy'],
            ],
            'Breakfast & Cereals' => [
                ['code' => 'DX/SUB/0018', 'name' => 'Cereals', 'desc' => 'Cornflakes and bran flakes'],
                ['code' => 'DX/SUB/0019', 'name' => 'Oats & Honey', 'desc' => 'Rolled oats and natural sweet honey jars'],
            ],
            'Canned & Packaged Goods' => [
                ['code' => 'DX/SUB/0020', 'name' => 'Canned Food', 'desc' => 'Canned tuna, sweetcorn, and baked beans'],
                ['code' => 'DX/SUB/0021', 'name' => 'Packaged Meals', 'desc' => 'Instant noodles and pre-packed meals'],
            ],
            'Condiments & Sauces' => [
                ['code' => 'DX/SUB/0022', 'name' => 'Table Sauces', 'desc' => 'Ketchup, mayo, mustard, hot sauces'],
                ['code' => 'DX/SUB/0023', 'name' => 'Pasta Sauces', 'desc' => 'Marinara, bolognese, alfredo sauces'],
            ],
            'Oils & Vinegars' => [
                ['code' => 'DX/SUB/0024', 'name' => 'Cooking Oils', 'desc' => 'Sunflower oil, olive oil, coconut oil'],
                ['code' => 'DX/SUB/0025', 'name' => 'Vinegars', 'desc' => 'White vinegar and apple cider vinegar'],
            ],
            'Spices & Seasonings' => [
                ['code' => 'DX/SUB/0026', 'name' => 'Ground Spices', 'desc' => 'Chili powder, turmeric, black pepper'],
                ['code' => 'DX/SUB/0027', 'name' => 'Herbs & Whole Spices', 'desc' => 'Garlic powder, oregano, cloves, cardamom'],
            ],
            'Rice, Grains & Pulses' => [
                ['code' => 'DX/SUB/0028', 'name' => 'Rice', 'desc' => 'Basmati, Red Raw, and Keeri Samba rice'],
                ['code' => 'DX/SUB/0029', 'name' => 'Lentils & Pulses', 'desc' => 'Red lentils, whole chickpeas, green gram'],
            ],
            'Organic & Natural' => [
                ['code' => 'DX/SUB/0030', 'name' => 'Organic Grains & Seeds', 'desc' => 'Organic chia seeds, quinoa, brown sugar'],
            ],
            'Health & Nutrition' => [
                ['code' => 'DX/SUB/0031', 'name' => 'Supplements', 'desc' => 'Multivitamins, whey protein, fish oil'],
            ],
            'Baby & Toddler' => [
                ['code' => 'DX/SUB/0032', 'name' => 'Baby Food', 'desc' => 'Infant milk powder and fruit purees'],
                ['code' => 'DX/SUB/0033', 'name' => 'Baby Care', 'desc' => 'Diapers, baby powder, sensitive wipes'],
            ],
            'Personal Care' => [
                ['code' => 'DX/SUB/0034', 'name' => 'Hair & Body', 'desc' => 'Shampoo, conditioner, and body wash soap'],
                ['code' => 'DX/SUB/0035', 'name' => 'Dental Care', 'desc' => 'Toothpaste and mouthwash'],
            ],
            'Household Cleaning' => [
                ['code' => 'DX/SUB/0036', 'name' => 'Detergents', 'desc' => 'Liquid laundry and dishwashing detergents'],
                ['code' => 'DX/SUB/0037', 'name' => 'Surface Cleaners', 'desc' => 'Glass spray and liquid floor cleaners'],
            ],
            'Paper & Plastic Goods' => [
                ['code' => 'DX/SUB/0038', 'name' => 'Paper Goods', 'desc' => 'Facial tissues, toilet paper rolls, napkins'],
                ['code' => 'DX/SUB/0039', 'name' => 'Plastic Goods', 'desc' => 'Garbage bags, cling wrap, zip-lock bags'],
            ],
            'Pet Supplies' => [
                ['code' => 'DX/SUB/0040', 'name' => 'Dog Food', 'desc' => 'Dry kibble, treats, and chew bones'],
                ['code' => 'DX/SUB/0041', 'name' => 'Cat Food', 'desc' => 'Canned cat food and dry treats'],
            ],
            'Alcohol & Spirits' => [
                ['code' => 'DX/SUB/0042', 'name' => 'Beer & Wine', 'desc' => 'Premium lager beers and red/white wines'],
            ],
            'Deli & Charcuterie' => [
                ['code' => 'DX/SUB/0043', 'name' => 'Cold Cuts', 'desc' => 'Smoked ham, salami slices, turkey breast'],
            ],
            'International Foods' => [
                ['code' => 'DX/SUB/0044', 'name' => 'Asian Staples', 'desc' => 'Ramen, sriracha, seaweed, wasabi'],
            ],
            'Seasonal & Promotions' => [
                ['code' => 'DX/SUB/0045', 'name' => 'Festive Goods', 'desc' => 'Christmas hampers, gift boxes, valentines candy'],
            ],
        ];

        $subcatIds = []; // Keyed by subcategory name, value is subcategory_id
        $subcatToCategory = []; // Keyed by subcategory_id, value is category_id

        foreach ($subcategoriesToSeed as $catName => $subList) {
            $catId = $categoryMap[$catName] ?? null;
            if (!$catId) {
                // If user deleted some category, fall back to first category
                $catId = count($categoryMap) > 0 ? reset($categoryMap) : 1;
            }

            foreach ($subList as $s) {
                $sub = SubCategory::updateOrCreate(
                    ['subcategory_code' => $s['code']],
                    [
                        'subcategory_name' => $s['name'],
                        'description' => $s['desc'],
                        'category_id' => $catId,
                        'created_date' => Carbon::now()->toDateString()
                    ]
                );
                $subcatIds[$s['name']] = $sub->subcategory_id;
                $subcatToCategory[$sub->subcategory_id] = $catId;
            }
        }

        // 5. Build 150 Products dataset distributed across categories
        $productsToSeed = [
            // Fruits (Category: Fresh Produce)
            ['name' => 'Red Gala Apple 1kg', 'sub' => 'Fruits', 'brand' => 'Keells', 'price' => 650.00, 'cost' => 520.00, 'unit' => 'kg', 'desc' => 'Crisp and sweet fresh red apples.'],
            ['name' => 'Cavendish Banana 1kg', 'sub' => 'Fruits', 'brand' => 'Keells', 'price' => 320.00, 'cost' => 240.00, 'unit' => 'kg', 'desc' => 'Fresh ripe yellow cavendish bananas.'],
            ['name' => 'Fresh Mandarin Orange 1kg', 'sub' => 'Fruits', 'brand' => 'Keells', 'price' => 780.00, 'cost' => 610.00, 'unit' => 'kg', 'desc' => 'Juicy, easy-to-peel sweet mandarin oranges.'],
            ['name' => 'Green Apple Granny Smith 1kg', 'sub' => 'Fruits', 'brand' => 'Keells', 'price' => 720.00, 'cost' => 580.00, 'unit' => 'kg', 'desc' => 'Tart and crisp green apples for baking or eating.'],
            ['name' => 'Whole Sweet Pineapple 1pc', 'sub' => 'Fruits', 'brand' => 'Keells', 'price' => 450.00, 'cost' => 350.00, 'unit' => 'pcs', 'desc' => 'Whole fresh ripe tropical pineapple.'],
            ['name' => 'Fresh Yellow Lemons 500g', 'sub' => 'Fruits', 'brand' => 'Keells', 'price' => 280.00, 'cost' => 210.00, 'unit' => 'g', 'desc' => 'Tangy fresh yellow imported lemons.'],

            // Vegetables (Category: Fresh Produce)
            ['name' => 'Local Tomatoes 1kg', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 380.00, 'cost' => 290.00, 'unit' => 'kg', 'desc' => 'Fresh local farm tomatoes.'],
            ['name' => 'Local Potato 1kg', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 220.00, 'cost' => 170.00, 'unit' => 'kg', 'desc' => 'Starchy white local potatoes.'],
            ['name' => 'Red Onions Local 1kg', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 550.00, 'cost' => 450.00, 'unit' => 'kg', 'desc' => 'Sharp-flavored red onions.'],
            ['name' => 'Fresh Carrots 1kg', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 390.00, 'cost' => 300.00, 'unit' => 'kg', 'desc' => 'Sweet, crunchy orange carrots.'],
            ['name' => 'Green Broccoli 500g', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 600.00, 'cost' => 480.00, 'unit' => 'g', 'desc' => 'Healthy green broccoli florets.'],
            ['name' => 'Local Cucumber 1kg', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 180.00, 'cost' => 130.00, 'unit' => 'kg', 'desc' => 'Crisp local green cucumbers.'],
            ['name' => 'Whole Garlic Bulbs 500g', 'sub' => 'Vegetables', 'brand' => 'Keells', 'price' => 490.00, 'cost' => 400.00, 'unit' => 'g', 'desc' => 'Pungent whole local garlic bulbs.'],

            // Bread & Buns (Category: Bakery)
            ['name' => 'White Sandwich Bread 450g', 'sub' => 'Bread & Buns', 'brand' => 'Keells', 'price' => 190.00, 'cost' => 150.00, 'unit' => 'pcs', 'desc' => 'Sliced soft white sandwich bread.'],
            ['name' => 'Wholemeal Sliced Bread 450g', 'sub' => 'Bread & Buns', 'brand' => 'Keells', 'price' => 230.00, 'cost' => 180.00, 'unit' => 'pcs', 'desc' => 'Healthy high-fiber wholemeal sliced bread.'],
            ['name' => 'Classic Burger Buns 4-pack', 'sub' => 'Bread & Buns', 'brand' => 'Keells', 'price' => 160.00, 'cost' => 120.00, 'unit' => 'pcs', 'desc' => 'Soft white sesame burger buns.'],
            ['name' => 'Hot Dog Rolls 4-pack', 'sub' => 'Bread & Buns', 'brand' => 'Keells', 'price' => 150.00, 'cost' => 110.00, 'unit' => 'pcs', 'desc' => 'Long soft rolls for hot dogs.'],
            ['name' => 'French Baguette Crusty 1pc', 'sub' => 'Bread & Buns', 'brand' => 'Keells', 'price' => 210.00, 'cost' => 160.00, 'unit' => 'pcs', 'desc' => 'Traditional crusty French bread baguette.'],

            // Cakes & Pastries (Category: Bakery)
            ['name' => 'Chocolate Fudge Cake 500g', 'sub' => 'Cakes & Pastries', 'brand' => 'Elephant House', 'price' => 1200.00, 'cost' => 900.00, 'unit' => 'pcs', 'desc' => 'Rich double chocolate fudge cake.'],
            ['name' => 'Butter Croissant 1pc', 'sub' => 'Cakes & Pastries', 'brand' => 'Keells', 'price' => 180.00, 'cost' => 130.00, 'unit' => 'pcs', 'desc' => 'Flaky, buttery bakery-fresh croissant.'],
            ['name' => 'Blueberry Muffin Large 1pc', 'sub' => 'Cakes & Pastries', 'brand' => 'Keells', 'price' => 220.00, 'cost' => 165.00, 'unit' => 'pcs', 'desc' => 'Moist sweet muffin loaded with blueberries.'],
            ['name' => 'Garlic Bread Baguette 1pc', 'sub' => 'Cakes & Pastries', 'brand' => 'Keells', 'price' => 280.00, 'cost' => 210.00, 'unit' => 'pcs', 'desc' => 'Bakery baguette spread with rich garlic butter.'],
            ['name' => 'Bakery Cinnamon Roll 1pc', 'sub' => 'Cakes & Pastries', 'brand' => 'Keells', 'price' => 190.00, 'cost' => 140.00, 'unit' => 'pcs', 'desc' => 'Sweet yeast roll with cinnamon filling and glaze.'],

            // Milk & Yogurt (Category: Dairy & Eggs)
            ['name' => 'Anchor Fresh Milk 1L', 'sub' => 'Milk & Yogurt', 'brand' => 'Anchor', 'price' => 450.00, 'cost' => 380.00, 'unit' => 'pcs', 'desc' => 'Pasteurized pure fresh cow milk.'],
            ['name' => 'Natural Set Yogurt 80g', 'sub' => 'Milk & Yogurt', 'brand' => 'Anchor', 'price' => 70.00, 'cost' => 55.00, 'unit' => 'pcs', 'desc' => 'Creamy natural probiotic set yogurt.'],
            ['name' => 'Low Fat Fresh Milk 1L', 'sub' => 'Milk & Yogurt', 'brand' => 'Anchor', 'price' => 480.00, 'cost' => 400.00, 'unit' => 'pcs', 'desc' => 'Pasteurized low-fat fresh cow milk.'],
            ['name' => 'Greek Style Set Yogurt 100g', 'sub' => 'Milk & Yogurt', 'brand' => 'Nestle', 'price' => 110.00, 'cost' => 85.00, 'unit' => 'pcs', 'desc' => 'Thick and creamy high-protein Greek yogurt.'],

            // Cheese & Butter (Category: Dairy & Eggs)
            ['name' => 'Cheddar Cheese Slices 200g', 'sub' => 'Cheese & Butter', 'brand' => 'Anchor', 'price' => 850.00, 'cost' => 700.00, 'unit' => 'pcs', 'desc' => 'Processed cheddar cheese singles.'],
            ['name' => 'Salted Table Butter 200g', 'sub' => 'Cheese & Butter', 'brand' => 'Anchor', 'price' => 690.00, 'cost' => 580.00, 'unit' => 'pcs', 'desc' => 'Creamy salted pure cream table butter.'],
            ['name' => 'Mozzarella Cheese Block 250g', 'sub' => 'Cheese & Butter', 'brand' => 'Anchor', 'price' => 950.00, 'cost' => 780.00, 'unit' => 'pcs', 'desc' => 'Shredding block mozzarella cheese for pizzas.'],
            ['name' => 'Cream Cheese Spread 200g', 'sub' => 'Cheese & Butter', 'brand' => 'Keells', 'price' => 790.00, 'cost' => 640.00, 'unit' => 'pcs', 'desc' => 'Creamy smooth spreadable cream cheese.'],

            // Eggs (Category: Dairy & Eggs)
            ['name' => 'Farm Fresh Eggs Pack of 10', 'sub' => 'Eggs', 'brand' => 'Keells', 'price' => 380.00, 'cost' => 310.00, 'unit' => 'pcs', 'desc' => 'Cleaned premium white chicken eggs.'],

            // Poultry (Category: Meat & Seafood)
            ['name' => 'Keells Chicken Breast 1kg', 'sub' => 'Poultry', 'brand' => 'Keells', 'price' => 1250.00, 'cost' => 1050.00, 'unit' => 'kg', 'desc' => 'Skinless, boneless chicken breast meat.'],
            ['name' => 'Keells Chicken Drumsticks 1kg', 'sub' => 'Poultry', 'brand' => 'Keells', 'price' => 1100.00, 'cost' => 920.00, 'unit' => 'kg', 'desc' => 'Fresh chicken legs/drumsticks.'],

            // Beef & Mutton (Category: Meat & Seafood)
            ['name' => 'Beef Porterhouse Steak 300g', 'sub' => 'Beef & Mutton', 'brand' => 'Keells', 'price' => 1800.00, 'cost' => 1500.00, 'unit' => 'pcs', 'desc' => 'Premium beef cut for searing steaks.'],
            ['name' => 'Beef Minced Lean 500g', 'sub' => 'Beef & Mutton', 'brand' => 'Keells', 'price' => 980.00, 'cost' => 820.00, 'unit' => 'pcs', 'desc' => 'Lean ground beef mince for cooking.'],
            ['name' => 'Mutton Curry Cut 500g', 'sub' => 'Beef & Mutton', 'brand' => 'Keells', 'price' => 1950.00, 'cost' => 1600.00, 'unit' => 'pcs', 'desc' => 'Fresh local mutton cut with bone.'],

            // Seafood (Category: Meat & Seafood)
            ['name' => 'Fresh Salmon Fillet 200g', 'sub' => 'Seafood', 'brand' => 'Keells', 'price' => 2400.00, 'cost' => 2000.00, 'unit' => 'pcs', 'desc' => 'Skin-on premium pink salmon fillet.'],
            ['name' => 'Yellowfin Tuna Steak 250g', 'sub' => 'Seafood', 'brand' => 'Keells', 'price' => 850.00, 'cost' => 690.00, 'unit' => 'pcs', 'desc' => 'Fresh local block yellowfin tuna steak.'],
            ['name' => 'Fresh Sea Crab Medium 1kg', 'sub' => 'Seafood', 'brand' => 'Keells', 'price' => 1650.00, 'cost' => 1350.00, 'unit' => 'kg', 'desc' => 'Whole fresh local blue swimmer crabs.'],

            // Frozen Snacks (Category: Frozen Foods)
            ['name' => 'Frozen Double Cheese Pizza 400g', 'sub' => 'Frozen Snacks', 'brand' => 'Prima', 'price' => 980.00, 'cost' => 790.00, 'unit' => 'pcs', 'desc' => 'Oven-ready frozen thin crust cheese pizza.'],
            ['name' => 'Frozen Straight Cut French Fries 1kg', 'sub' => 'Frozen Snacks', 'brand' => 'Prima', 'price' => 820.00, 'cost' => 640.00, 'unit' => 'pcs', 'desc' => 'Frozen straight cut potato fries.'],
            ['name' => 'Frozen Chicken Nuggets Box 500g', 'sub' => 'Frozen Snacks', 'brand' => 'Keells', 'price' => 780.00, 'cost' => 620.00, 'unit' => 'pcs', 'desc' => 'Crispy breaded frozen chicken breast nuggets.'],
            ['name' => 'Frozen Veg Spring Rolls 10-pack', 'sub' => 'Frozen Snacks', 'brand' => 'Prima', 'price' => 450.00, 'cost' => 360.00, 'unit' => 'pcs', 'desc' => 'Crispy frozen vegetable spring rolls.'],

            // Ice Cream (Category: Frozen Foods)
            ['name' => 'EH Vanilla Ice Cream 1L', 'sub' => 'Ice Cream', 'brand' => 'Elephant House', 'price' => 380.00, 'cost' => 300.00, 'unit' => 'pcs', 'desc' => 'Classic creamy vanilla ice cream tub.'],
            ['name' => 'EH Chocolate Ice Cream 1L', 'sub' => 'Ice Cream', 'brand' => 'Elephant House', 'price' => 400.00, 'cost' => 320.00, 'unit' => 'pcs', 'desc' => 'Rich double chocolate ice cream tub.'],
            ['name' => 'EH Strawberry Ice Cream 1L', 'sub' => 'Ice Cream', 'brand' => 'Elephant House', 'price' => 400.00, 'cost' => 320.00, 'unit' => 'pcs', 'desc' => 'Sweet creamy strawberry ice cream tub.'],

            // Soft Drinks (Category: Beverages)
            ['name' => 'Coca-Cola Regular 1.5L', 'sub' => 'Soft Drinks', 'brand' => 'Coca-Cola Company', 'price' => 350.00, 'cost' => 280.00, 'unit' => 'pcs', 'desc' => 'Carbonated regular cola soft drink.'],
            ['name' => 'Sprite Regular 1.5L', 'sub' => 'Soft Drinks', 'brand' => 'Coca-Cola Company', 'price' => 350.00, 'cost' => 280.00, 'unit' => 'pcs', 'desc' => 'Fizzy lemon-lime flavored soft drink.'],
            ['name' => 'Fanta Orange 1.5L', 'sub' => 'Soft Drinks', 'brand' => 'Coca-Cola Company', 'price' => 350.00, 'cost' => 280.00, 'unit' => 'pcs', 'desc' => 'Carbonated orange flavored soft drink.'],
            ['name' => 'EH Cream Soda 1.5L', 'sub' => 'Soft Drinks', 'brand' => 'Elephant House', 'price' => 330.00, 'cost' => 265.00, 'unit' => 'pcs', 'desc' => 'Fizzy green vanilla cream soda drink.'],
            ['name' => 'EH Ginger Beer EGB 1.5L', 'sub' => 'Soft Drinks', 'brand' => 'Elephant House', 'price' => 340.00, 'cost' => 275.00, 'unit' => 'pcs', 'desc' => 'Traditional fiery ginger beer carbonated drink.'],

            // Juices & Water (Category: Beverages)
            ['name' => 'Bottled Drinking Water 500ml', 'sub' => 'Juices & Water', 'brand' => 'Keells', 'price' => 60.00, 'cost' => 40.00, 'unit' => 'pcs', 'desc' => 'Pure mineral drinking water bottle.'],
            ['name' => 'Orange Juice Carton 1L', 'sub' => 'Juices & Water', 'brand' => 'Nestle', 'price' => 490.00, 'cost' => 390.00, 'unit' => 'pcs', 'desc' => '100% natural orange fruit juice pack.'],
            ['name' => 'Club Soda Water Can 325ml', 'sub' => 'Juices & Water', 'brand' => 'Elephant House', 'price' => 90.00, 'cost' => 70.00, 'unit' => 'pcs', 'desc' => 'Carbonated refreshing club soda.'],

            // Tea & Coffee (Category: Beverages)
            ['name' => 'Watawala Tea Blend 500g', 'sub' => 'Tea & Coffee', 'brand' => 'Keells', 'price' => 680.00, 'cost' => 550.00, 'unit' => 'pcs', 'desc' => 'Strong black tea powder blend.'],
            ['name' => 'Nescafe Classic Coffee 100g', 'sub' => 'Tea & Coffee', 'brand' => 'Nestle', 'price' => 890.00, 'cost' => 730.00, 'unit' => 'pcs', 'desc' => 'Pure soluble instant coffee granules.'],
            ['name' => 'Dilmah Green Tea 20 Bags', 'sub' => 'Tea & Coffee', 'brand' => 'Keells', 'price' => 420.00, 'cost' => 330.00, 'unit' => 'pcs', 'desc' => 'Pure Ceylon green tea filter bags.'],

            // Chips & Crackers (Category: Snacks & Confectionery)
            ['name' => 'Classic Salted Potato Chips 100g', 'sub' => 'Chips & Crackers', 'brand' => 'Keells', 'price' => 240.00, 'cost' => 190.00, 'unit' => 'pcs', 'desc' => 'Crispy salted sliced potato chips.'],
            ['name' => 'Munchee Super Cream Crackers 200g', 'sub' => 'Chips & Crackers', 'brand' => 'Munchee', 'price' => 180.00, 'cost' => 140.00, 'unit' => 'pcs', 'desc' => 'Square flaky salted cream crackers.'],
            ['name' => 'Maliban Gold Marie Biscuits 80g', 'sub' => 'Chips & Crackers', 'brand' => 'Maliban', 'price' => 80.00, 'cost' => 65.00, 'unit' => 'pcs', 'desc' => 'Crisp sweet vanilla marie biscuits.'],
            ['name' => 'Cheese Button Crackers 150g', 'sub' => 'Chips & Crackers', 'brand' => 'Munchee', 'price' => 160.00, 'cost' => 125.00, 'unit' => 'pcs', 'desc' => 'Bite-sized cheese crackers.'],

            // Chocolates & Sweets (Category: Snacks & Confectionery)
            ['name' => 'Nestle KitKat 4-Finger 45g', 'sub' => 'Chocolates & Sweets', 'brand' => 'Nestle', 'price' => 290.00, 'cost' => 230.00, 'unit' => 'pcs', 'desc' => 'Crispy wafer fingers covered in milk chocolate.'],
            ['name' => 'Cadbury Dairy Milk Chocolate 90g', 'sub' => 'Chocolates & Sweets', 'brand' => 'Keells', 'price' => 450.00, 'cost' => 370.00, 'unit' => 'pcs', 'desc' => 'Creamy milk chocolate bar.'],
            ['name' => 'Sour Fruit Gummy Bears 150g', 'sub' => 'Chocolates & Sweets', 'brand' => 'Keells', 'price' => 320.00, 'cost' => 250.00, 'unit' => 'pcs', 'desc' => 'Chewy sour sugared gummy candies.'],

            // Cereals (Category: Breakfast & Cereals)
            ['name' => 'Nestle Cornflakes Box 350g', 'sub' => 'Cereals', 'brand' => 'Nestle', 'price' => 790.00, 'cost' => 640.00, 'unit' => 'pcs', 'desc' => 'Crispy toasted golden corn flakes.'],
            ['name' => 'Milo Breakfast Cereal 250g', 'sub' => 'Cereals', 'brand' => 'Nestle', 'price' => 680.00, 'cost' => 550.00, 'unit' => 'pcs', 'desc' => 'Chocolate-malt flavored breakfast cereal balls.'],

            // Oats & Honey (Category: Breakfast & Cereals)
            ['name' => 'Rolled Oats Oats Pack 500g', 'sub' => 'Oats & Honey', 'brand' => 'Keells', 'price' => 480.00, 'cost' => 390.00, 'unit' => 'pcs', 'desc' => '100% whole grain rolled cooking oats.'],
            ['name' => 'Pure Natural Honey Jar 250ml', 'sub' => 'Oats & Honey', 'brand' => 'Keells', 'price' => 850.00, 'cost' => 690.00, 'unit' => 'pcs', 'desc' => '100% pure wild natural honey.'],

            // Canned Food (Category: Canned & Packaged Goods)
            ['name' => 'Canned Tuna in Vegetable Oil 185g', 'sub' => 'Canned Food', 'brand' => 'Keells', 'price' => 390.00, 'cost' => 310.00, 'unit' => 'pcs', 'desc' => 'Flaked tuna chunks preserved in oil.'],
            ['name' => 'Baked Beans in Tomato Sauce 425g', 'sub' => 'Canned Food', 'brand' => 'Keells', 'price' => 280.00, 'cost' => 220.00, 'unit' => 'pcs', 'desc' => 'White haricot beans in thick tomato sauce.'],
            ['name' => 'Canned Peach Slices in Syrup 825g', 'sub' => 'Canned Food', 'brand' => 'Keells', 'price' => 780.00, 'cost' => 620.00, 'unit' => 'pcs', 'desc' => 'Sweet yellow peach slices in light syrup.'],

            // Packaged Meals (Category: Canned & Packaged Goods)
            ['name' => 'Prima Kottu Mee Instant Noodles 5-pack', 'sub' => 'Packaged Meals', 'brand' => 'Prima', 'price' => 390.00, 'cost' => 310.00, 'unit' => 'pcs', 'desc' => 'Spicy instant noodles with seasoning.'],

            // Table Sauces (Category: Condiments & Sauces)
            ['name' => 'Tomato Ketchup Bottle 400g', 'sub' => 'Table Sauces', 'brand' => 'Keells', 'price' => 320.00, 'cost' => 250.00, 'unit' => 'pcs', 'desc' => 'Tangy sweet tomato ketchup.'],
            ['name' => 'Real Egg Mayonnaise Jar 250g', 'sub' => 'Table Sauces', 'brand' => 'Keells', 'price' => 450.00, 'cost' => 360.00, 'unit' => 'pcs', 'desc' => 'Thick and creamy real egg mayonnaise.'],
            ['name' => 'Sweet Chili Dipping Sauce 350g', 'sub' => 'Table Sauces', 'brand' => 'Keells', 'price' => 380.00, 'cost' => 295.00, 'unit' => 'pcs', 'desc' => 'Spicy-sweet dipping sauce for snacks.'],
            ['name' => 'Original BBQ Sauce Bottle 400g', 'sub' => 'Table Sauces', 'brand' => 'Keells', 'price' => 490.00, 'cost' => 390.00, 'unit' => 'pcs', 'desc' => 'Smoky hickory flavored barbecue sauce.'],

            // Pasta Sauces (Category: Condiments & Sauces)
            ['name' => 'Pasta Marinara Tomato Sauce 350g', 'sub' => 'Pasta Sauces', 'brand' => 'Keells', 'price' => 590.00, 'cost' => 470.00, 'unit' => 'pcs', 'desc' => 'Italian herb tomato sauce for pasta.'],

            // Cooking Oils (Category: Oils & Vinegars)
            ['name' => 'Fortune Sunflower Oil 1L', 'sub' => 'Cooking Oils', 'brand' => 'Keells', 'price' => 750.00, 'cost' => 610.00, 'unit' => 'pcs', 'desc' => 'Refined healthy sunflower cooking oil.'],
            ['name' => 'Extra Virgin Olive Oil 500ml', 'sub' => 'Cooking Oils', 'brand' => 'Keells', 'price' => 1450.00, 'cost' => 1180.00, 'unit' => 'pcs', 'desc' => 'Cold pressed extra virgin olive oil.'],
            ['name' => 'Pure Coconut Cooking Oil 1L', 'sub' => 'Cooking Oils', 'brand' => 'Keells', 'price' => 620.00, 'cost' => 500.00, 'unit' => 'pcs', 'desc' => '100% natural double filtered coconut oil.'],

            // Vinegars (Category: Oils & Vinegars)
            ['name' => 'White Synthetic Vinegar 350ml', 'sub' => 'Vinegars', 'brand' => 'Keells', 'price' => 120.00, 'cost' => 90.00, 'unit' => 'pcs', 'desc' => 'Acetic acid white synthetic cooking vinegar.'],

            // Ground Spices (Category: Spices & Seasonings)
            ['name' => 'Red Chili Powder Pack 100g', 'sub' => 'Ground Spices', 'brand' => 'Keells', 'price' => 190.00, 'cost' => 150.00, 'unit' => 'pcs', 'desc' => 'Hot ground red chili powder.'],
            ['name' => 'Turmeric Powder Pack 100g', 'sub' => 'Ground Spices', 'brand' => 'Keells', 'price' => 220.00, 'cost' => 170.00, 'unit' => 'pcs', 'desc' => 'Ground organic yellow turmeric powder.'],
            ['name' => 'Black Pepper Powder Grinder 50g', 'sub' => 'Ground Spices', 'brand' => 'Keells', 'price' => 380.00, 'cost' => 300.00, 'unit' => 'pcs', 'desc' => 'Fresh black pepper corns in hand grinder.'],

            // Herbs & Whole Spices (Category: Spices & Seasonings)
            ['name' => 'Dried Oregano Leaves Jar 30g', 'sub' => 'Herbs & Whole Spices', 'brand' => 'Keells', 'price' => 290.00, 'cost' => 220.00, 'unit' => 'pcs', 'desc' => 'Aromatic dried oregano herb leaves.'],
            ['name' => 'Whole Green Cardamom 50g', 'sub' => 'Herbs & Whole Spices', 'brand' => 'Keells', 'price' => 680.00, 'cost' => 530.00, 'unit' => 'pcs', 'desc' => 'Premium whole green cardamom pods.'],

            // Rice (Category: Rice, Grains & Pulses)
            ['name' => 'Basmati Rice Premium 5kg', 'sub' => 'Rice', 'brand' => 'Keells', 'price' => 2200.00, 'cost' => 1800.00, 'unit' => 'pcs', 'desc' => 'Long grain fragrant basmati rice bag.'],
            ['name' => 'Red Raw Rice Local 5kg', 'sub' => 'Rice', 'brand' => 'Keells', 'price' => 1150.00, 'cost' => 950.00, 'unit' => 'pcs', 'desc' => 'High fiber local red raw rice bag.'],
            ['name' => 'Keeri Samba Rice 5kg', 'sub' => 'Rice', 'brand' => 'Keells', 'price' => 1450.00, 'cost' => 1200.00, 'unit' => 'pcs', 'desc' => 'Premium short grain local samba rice bag.'],

            // Lentils & Pulses (Category: Rice, Grains & Pulses)
            ['name' => 'Split Red Lentils dhal 1kg', 'sub' => 'Lentils & Pulses', 'brand' => 'Keells', 'price' => 380.00, 'cost' => 310.00, 'unit' => 'pcs', 'desc' => 'Polished split red lentils (mysore dhal).'],
            ['name' => 'Whole White Chickpeas 1kg', 'sub' => 'Lentils & Pulses', 'brand' => 'Keells', 'price' => 520.00, 'cost' => 420.00, 'unit' => 'pcs', 'desc' => 'Dried whole white chickpeas (kabuli chana).'],

            // Organic Grains & Seeds (Category: Organic & Natural)
            ['name' => 'Organic Chia Seeds Jar 200g', 'sub' => 'Organic Grains & Seeds', 'brand' => 'Keells', 'price' => 890.00, 'cost' => 700.00, 'unit' => 'pcs', 'desc' => 'Raw organic black chia seeds.'],
            ['name' => 'Organic White Quinoa Grain 500g', 'sub' => 'Organic Grains & Seeds', 'brand' => 'Keells', 'price' => 1250.00, 'cost' => 1020.00, 'unit' => 'pcs', 'desc' => 'Organic gluten-free white quinoa seeds.'],
            ['name' => 'Organic Brown Sugar Pack 1kg', 'sub' => 'Organic Grains & Seeds', 'brand' => 'Keells', 'price' => 380.00, 'cost' => 300.00, 'unit' => 'pcs', 'desc' => 'Unrefined organic brown cane sugar.'],

            // Supplements (Category: Health & Nutrition)
            ['name' => 'Daily Multivitamin 60 Capsules', 'sub' => 'Supplements', 'brand' => 'Nestle', 'price' => 2400.00, 'cost' => 1950.00, 'unit' => 'pcs', 'desc' => 'Daily essential vitamin & mineral capsules.'],
            ['name' => 'Whey Protein Powder Chocolate 1kg', 'sub' => 'Supplements', 'brand' => 'Nestle', 'price' => 9500.00, 'cost' => 8000.00, 'unit' => 'pcs', 'desc' => 'High quality whey protein isolate powder.'],
            ['name' => 'Omega-3 Fish Oil 1000mg 90 Softgels', 'sub' => 'Supplements', 'brand' => 'Nestle', 'price' => 3200.00, 'cost' => 2600.00, 'unit' => 'pcs', 'desc' => 'EPA/DHA heart-healthy fish oil softgels.'],

            // Baby Food (Category: Baby & Toddler)
            ['name' => 'Lactogen Baby Formula Step-1 400g', 'sub' => 'Baby Food', 'brand' => 'Nestle', 'price' => 1450.00, 'cost' => 1250.00, 'unit' => 'pcs', 'desc' => 'Infant milk formula powder for 0-6 months.'],
            ['name' => 'Apple & Banana Puree Baby Jar 110g', 'sub' => 'Baby Food', 'brand' => 'Nestle', 'price' => 320.00, 'cost' => 260.00, 'unit' => 'pcs', 'desc' => 'Organic blended fruit baby food jar.'],

            // Baby Care (Category: Baby & Toddler)
            ['name' => 'Baby Diapers Size M 32-pack', 'sub' => 'Baby Care', 'brand' => 'P&G', 'price' => 2200.00, 'cost' => 1800.00, 'unit' => 'pcs', 'desc' => 'Ultra-absorbent baby diapers.'],
            ['name' => 'Sensitive Baby Wet Wipes 80-pack', 'sub' => 'Baby Care', 'brand' => 'P&G', 'price' => 450.00, 'cost' => 360.00, 'unit' => 'pcs', 'desc' => 'Alcohol-free fragrance-free baby wipes.'],

            // Hair & Body (Category: Personal Care)
            ['name' => 'Clear Anti-Dandruff Shampoo 350ml', 'sub' => 'Hair & Body', 'brand' => 'Unilever', 'price' => 690.00, 'cost' => 560.00, 'unit' => 'pcs', 'desc' => 'Cool menthol anti-dandruff shampoo.'],
            ['name' => 'Lux Soft Touch Bar Soap 100g', 'sub' => 'Hair & Body', 'brand' => 'Unilever', 'price' => 140.00, 'cost' => 110.00, 'unit' => 'pcs', 'desc' => 'Moisturizing floral beauty soap bar.'],
            ['name' => 'Dove Body Wash Deep Moisture 500ml', 'sub' => 'Hair & Body', 'brand' => 'Unilever', 'price' => 1250.00, 'cost' => 1020.00, 'unit' => 'pcs', 'desc' => 'Nourishing deep moisture liquid body wash.'],

            // Dental Care (Category: Personal Care)
            ['name' => 'Signal Herbal Toothpaste 160g', 'sub' => 'Dental Care', 'brand' => 'Unilever', 'price' => 220.00, 'cost' => 180.00, 'unit' => 'pcs', 'desc' => 'Fluoride toothpaste with natural herbs.'],
            ['name' => 'Listerine Cool Mint Mouthwash 250ml', 'sub' => 'Dental Care', 'brand' => 'P&G', 'price' => 590.00, 'cost' => 480.00, 'unit' => 'pcs', 'desc' => 'Antiseptic mouthwash for fresh breath.'],

            // Detergents (Category: Household Cleaning)
            ['name' => 'Surf Excel Liquid Detergent 1L', 'sub' => 'Detergents', 'brand' => 'Unilever', 'price' => 850.00, 'cost' => 700.00, 'unit' => 'pcs', 'desc' => 'Liquid washing machine laundry detergent.'],
            ['name' => 'Vim Dishwashing Liquid Bottle 500ml', 'sub' => 'Detergents', 'brand' => 'Unilever', 'price' => 290.00, 'cost' => 230.00, 'unit' => 'pcs', 'desc' => 'Lemon grease-cutting dishwashing liquid.'],

            // Surface Cleaners (Category: Household Cleaning)
            ['name' => 'Lysol Floor Cleaner Liquid 1L', 'sub' => 'Surface Cleaners', 'brand' => 'Unilever', 'price' => 650.00, 'cost' => 520.00, 'unit' => 'pcs', 'desc' => 'Disinfectant citrus liquid floor cleaner.'],
            ['name' => 'Colin Glass Cleaner Spray 500ml', 'sub' => 'Surface Cleaners', 'brand' => 'Unilever', 'price' => 380.00, 'cost' => 300.00, 'unit' => 'pcs', 'desc' => 'Blue glass and multi-surface cleaner spray.'],

            // Paper Goods (Category: Paper & Plastic Goods)
            ['name' => 'Flora Facial Tissues 200-sheets', 'sub' => 'Paper Goods', 'brand' => 'Keells', 'price' => 280.00, 'cost' => 220.00, 'unit' => 'pcs', 'desc' => 'Soft 2-ply white facial tissues box.'],
            ['name' => 'Toilet Paper Rolls 4-pack', 'sub' => 'Paper Goods', 'brand' => 'Keells', 'price' => 390.00, 'cost' => 310.00, 'unit' => 'pcs', 'desc' => 'Soft embossed white toilet tissue rolls.'],

            // Plastic Goods (Category: Paper & Plastic Goods)
            ['name' => 'Garbage Bags Large 10-pack', 'sub' => 'Plastic Goods', 'brand' => 'Keells', 'price' => 290.00, 'cost' => 220.00, 'unit' => 'pcs', 'desc' => 'Heavy duty black plastic garbage bags.'],
            ['name' => 'Cling Wrap Food Film 30m', 'sub' => 'Plastic Goods', 'brand' => 'Keells', 'price' => 340.00, 'cost' => 270.00, 'unit' => 'pcs', 'desc' => 'Transparent stretchable food wrap film.'],

            // Dog Food (Category: Pet Supplies)
            ['name' => 'Pedigree Dry Dog Food Chicken 3kg', 'sub' => 'Dog Food', 'brand' => 'Keells', 'price' => 2450.00, 'cost' => 2050.00, 'unit' => 'pcs', 'desc' => 'Dry kibble dog food for adult dogs.'],
            ['name' => 'Pedigree Meat Gravy Dog Can 400g', 'sub' => 'Dog Food', 'brand' => 'Keells', 'price' => 450.00, 'cost' => 370.00, 'unit' => 'pcs', 'desc' => 'Wet dog food chunks in gravy can.'],

            // Cat Food (Category: Pet Supplies)
            ['name' => 'Whiskas Canned Cat Food Tuna 400g', 'sub' => 'Cat Food', 'brand' => 'Keells', 'price' => 420.00, 'cost' => 340.00, 'unit' => 'pcs', 'desc' => 'Wet canned cat food with real tuna.'],
            ['name' => 'Whiskas Dry Cat Food Salmon 1.2kg', 'sub' => 'Cat Food', 'brand' => 'Keells', 'price' => 1450.00, 'cost' => 1200.00, 'unit' => 'pcs', 'desc' => 'Dry salmon kibble cat food.'],

            // Beer & Wine (Category: Alcohol & Spirits)
            ['name' => 'Lion Lager Beer Can 500ml', 'sub' => 'Beer & Wine', 'brand' => 'Elephant House', 'price' => 490.00, 'cost' => 410.00, 'unit' => 'pcs', 'desc' => 'Premium local lager beer can.'],
            ['name' => 'Cabernet Sauvignon Red Wine 750ml', 'sub' => 'Beer & Wine', 'brand' => 'Keells', 'price' => 3500.00, 'cost' => 2900.00, 'unit' => 'pcs', 'desc' => 'Dry red grape table wine bottle.'],

            // Cold Cuts (Category: Deli & Charcuterie)
            ['name' => 'Smoked Chicken Ham Slices 200g', 'sub' => 'Cold Cuts', 'brand' => 'Keells', 'price' => 690.00, 'cost' => 550.00, 'unit' => 'pcs', 'desc' => 'Fully cooked smoked chicken breast ham.'],
            ['name' => 'Peppered Pork Salami 150g', 'sub' => 'Cold Cuts', 'brand' => 'Keells', 'price' => 850.00, 'cost' => 690.00, 'unit' => 'pcs', 'desc' => 'Spiced pork salami cold cut slices.'],

            // Asian Staples (Category: International Foods)
            ['name' => 'Sriracha Hot Chili Sauce 450g', 'sub' => 'Asian Staples', 'brand' => 'Keells', 'price' => 890.00, 'cost' => 720.00, 'unit' => 'pcs', 'desc' => 'Spicy Thai sriracha chili sauce.'],
            ['name' => 'Dry Ramen Noodles Spicy 5-pack', 'sub' => 'Asian Staples', 'brand' => 'Prima', 'price' => 750.00, 'cost' => 600.00, 'unit' => 'pcs', 'desc' => 'Instant Japanese style spicy ramen noodles.'],

            // Festive Goods (Category: Seasonal & Promotions)
            ['name' => 'Christmas Chocolate Gift Box', 'sub' => 'Festive Goods', 'brand' => 'Nestle', 'price' => 1850.00, 'cost' => 1400.00, 'unit' => 'pcs', 'desc' => 'Assorted milk chocolates in holiday gift pack.'],
            ['name' => 'Birthday Candles Multi-color 24pc', 'sub' => 'Festive Goods', 'brand' => 'Keells', 'price' => 150.00, 'cost' => 100.00, 'unit' => 'pcs', 'desc' => 'Colorful wax birthday cake candles.'],
        ];

        // Now, we need to generate up to 150 items to fulfill the requirement.
        // Let's programmatically duplicate/vary the remaining items to reach exactly 150 unique products
        $currentCount = count($productsToSeed);
        $needed = 150 - $currentCount;

        // Base products to vary from
        $variations = [
            ['suffix' => 'Value Pack', 'price_mult' => 1.8, 'cost_mult' => 1.8, 'unit' => 'pcs'],
            ['suffix' => 'Eco Friendly', 'price_mult' => 1.1, 'cost_mult' => 1.05, 'unit' => 'pcs'],
            ['suffix' => 'Premium Blend', 'price_mult' => 1.3, 'cost_mult' => 1.25, 'unit' => 'pcs'],
            ['suffix' => 'Family Pack', 'price_mult' => 2.5, 'cost_mult' => 2.4, 'unit' => 'pcs'],
            ['suffix' => 'Imported', 'price_mult' => 1.5, 'cost_mult' => 1.4, 'unit' => 'pcs'],
        ];

        for ($i = 0; $i < $needed; $i++) {
            $baseProduct = $productsToSeed[$i % $currentCount];
            $variation = $variations[($i / $currentCount) % count($variations)];
            
            $productsToSeed[] = [
                'name' => $baseProduct['name'] . ' ' . $variation['suffix'],
                'sub' => $baseProduct['sub'],
                'brand' => $baseProduct['brand'],
                'price' => round($baseProduct['price'] * $variation['price_mult'], 2),
                'cost' => round($baseProduct['cost'] * $variation['cost_mult'], 2),
                'unit' => $baseProduct['unit'],
                'desc' => $baseProduct['desc'] . ' (' . $variation['suffix'] . ' edition).'
            ];
        }

        // Slice to exactly 150 products
        $productsToSeed = array_slice($productsToSeed, 0, 150);

        // 6. Insert Products & create Stock records
        $productCount = 1;
        foreach ($productsToSeed as $p) {
            $subcatId = $subcatIds[$p['sub']] ?? null;
            if (!$subcatId) {
                // Fallback to first available subcategory
                $subcatId = reset($subcatIds);
            }

            $catId = $subcatToCategory[$subcatId] ?? 1;

            $brandId = $brandIds[$p['brand']] ?? reset($brandIds);
            $supplierId = $supplierIds[array_rand($supplierIds)];

            // Generate unique product code like DX/ITM/0001
            $prodCode = 'DX/ITM/' . str_pad($productCount, 4, '0', STR_PAD_LEFT);
            // Generate standard barcode e.g., 400012345001
            $barcode = '400012345' . str_pad($productCount, 3, '0', STR_PAD_LEFT);

            $product = Product::updateOrCreate(
                ['product_code' => $prodCode],
                [
                    'product_name' => $p['name'],
                    'brand_id' => $brandId,
                    'subcategory_id' => $subcatId,
                    'price' => $p['price'],
                    'unit' => $p['unit'],
                    'status' => 'active',
                    'description' => $p['desc'],
                    'user_id' => 1, // Created by our created admin user
                    'barcode' => $barcode,
                    'cost_price' => $p['cost'],
                    'weight' => rand(1, 20) / 10, // 0.1 to 2.0 kg
                    'dimensions' => rand(5, 30) . 'x' . rand(5, 30) . 'x' . rand(5, 30),
                    'supplier_id' => $supplierId,
                ]
            );

            // Create Stock records for this product across branches
            // Main Branch (ID: 1) - higher stock
            Stock::updateOrCreate(
                [
                    'product_id' => $product->product_id,
                    'branch_id' => 1
                ],
                [
                    'brand_id' => $brandId,
                    'category_id' => $catId,
                    'subcategory_id' => $subcatId,
                    'product_name' => $product->product_name,
                    'quantity' => rand(30, 150),
                    'minimum_stock_level' => 10,
                    'maximum_stock_level' => 500,
                    'last_restocked_date' => Carbon::now()->toDateString(),
                    'location_in_warehouse' => 'Aisle ' . rand(1, 15) . ' Rack ' . rand(1, 5)
                ]
            );

            // Colombo Branch (ID: 2) - moderate stock
            Stock::updateOrCreate(
                [
                    'product_id' => $product->product_id,
                    'branch_id' => 2
                ],
                [
                    'brand_id' => $brandId,
                    'category_id' => $catId,
                    'subcategory_id' => $subcatId,
                    'product_name' => $product->product_name,
                    'quantity' => rand(15, 80),
                    'minimum_stock_level' => 5,
                    'maximum_stock_level' => 300,
                    'last_restocked_date' => Carbon::now()->toDateString(),
                    'location_in_warehouse' => 'Aisle ' . rand(1, 10) . ' Rack ' . rand(1, 3)
                ]
            );

            $productCount++;
        }
    }
}
