<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\InvPara;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Store;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        InvoiceItem::truncate();
        Invoice::truncate();
        PurchaseItem::truncate();
        Purchase::truncate();
        Order::truncate();
        StockTransaction::truncate();
        Stock::truncate();
        Product::truncate();
        Customer::truncate();
        Bank::truncate();
        Store::truncate();
        Employee::truncate();
        Department::truncate();
        Branch::truncate();
        Supplier::truncate();
        Brand::truncate();
        SubCategory::truncate();
        Category::truncate();
        InvPara::truncate();
        User::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        for ($i = 1; $i <= 4; $i++) {
            User::create([
                'name' => "Employee $i",
                'email' => "employee$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]);
        }

        // 2. Seed InvPara
        InvPara::create([
            'company_flag' => 1,
            'company_name' => 'Nexus ERP Solutions',
            'company_address' => '456 Innovation Drive, Colombo, Sri Lanka',
            'email' => 'support@nexuserp.com',
            'phone_no_01' => '+94112233445',
            'phone_no_02' => '+94112233446',
            'website' => 'www.nexuserp.com',
            'product_code' => 100,
            'brand_code' => 10,
            'category_code' => 5,
            'invoice_no' => 1000,
            'grn_no' => 2000,
            'branch_code' => 5,
            'bank_code' => 5,
            'customer_code' => 15,
            'employee_code' => 10,
            'supplier_code' => 10,
            'department_code' => 5,
            'store_code' => 5,
            'status' => 'active',
            'package' => 'premium'
        ]);

        // 3. Seed Categories
        $categoriesData = [
            ['category_code' => 'CAT001', 'category_name' => 'Electronics', 'description' => 'Gadgets, devices and accessories', 'is_active' => true],
            ['category_code' => 'CAT002', 'category_name' => 'Apparel', 'description' => 'Clothing, footwear and wearable gear', 'is_active' => true],
            ['category_code' => 'CAT003', 'category_name' => 'Home & Kitchen', 'description' => 'Appliances, cooking tools and smart home devices', 'is_active' => true],
            ['category_code' => 'CAT004', 'category_name' => 'Sports & Outdoors', 'description' => 'Fitness equipment, sporting goods and outdoor gear', 'is_active' => true],
            ['category_code' => 'CAT005', 'category_name' => 'Office Supplies', 'description' => 'Stationery, desk organizers and office electronics', 'is_active' => true],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = Category::create(array_merge($cat, ['created_date' => Carbon::now()]));
        }

        // 4. Seed Subcategories
        $subcategoriesMapping = [
            'CAT001' => ['Smartphones', 'Laptops', 'Audio', 'Accessories', 'Smart Home'],
            'CAT002' => ['T-Shirts', 'Jeans', 'Jackets', 'Socks', 'Caps'],
            'CAT003' => ['Coffee Makers', 'Microwaves', 'Blenders', 'Toasters', 'Air Fryers'],
            'CAT004' => ['Footballs', 'Dumbbells', 'Yoga Mats', 'Water Bottles', 'Running Shoes'],
            'CAT005' => ['Notebooks', 'Pens', 'Desk Organizers', 'Staplers', 'Paper Shredders'],
        ];

        $subcategories = [];
        $subIndex = 1;
        foreach ($categories as $category) {
            $subList = $subcategoriesMapping[$category->category_code];
            foreach ($subList as $subName) {
                $code = 'SUB' . str_pad($subIndex++, 3, '0', STR_PAD_LEFT);
                $subcategories[$subName] = SubCategory::create([
                    'subcategory_code' => $code,
                    'subcategory_name' => $subName,
                    'description' => "Sample description for $subName",
                    'category_id' => $category->category_id,
                    'created_date' => Carbon::now(),
                ]);
            }
        }

        // 5. Seed Brands
        $brandsData = [
            ['brand_code' => 'BRD001', 'brand_name' => 'Samsung', 'contact_person' => 'Lee Kun', 'phone' => '+94771234560', 'email' => 'samsung@corp.com', 'website' => 'samsung.com'],
            ['brand_code' => 'BRD002', 'brand_name' => 'Apple', 'contact_person' => 'Tim Cook', 'phone' => '+94771234561', 'email' => 'apple@corp.com', 'website' => 'apple.com'],
            ['brand_code' => 'BRD003', 'brand_name' => 'Sony', 'contact_person' => 'Ken Yoshida', 'phone' => '+94771234562', 'email' => 'sony@corp.com', 'website' => 'sony.com'],
            ['brand_code' => 'BRD004', 'brand_name' => 'Nike', 'contact_person' => 'Phil Knight', 'phone' => '+94771234563', 'email' => 'nike@corp.com', 'website' => 'nike.com'],
            ['brand_code' => 'BRD005', 'brand_name' => 'Adidas', 'contact_person' => 'Bjorn Gulden', 'phone' => '+94771234564', 'email' => 'adidas@corp.com', 'website' => 'adidas.com'],
            ['brand_code' => 'BRD006', 'brand_name' => 'Dell', 'contact_person' => 'Michael Dell', 'phone' => '+94771234565', 'email' => 'dell@corp.com', 'website' => 'dell.com'],
            ['brand_code' => 'BRD007', 'brand_name' => 'HP', 'contact_person' => 'Enrique Lores', 'phone' => '+94771234566', 'email' => 'hp@corp.com', 'website' => 'hp.com'],
            ['brand_code' => 'BRD008', 'brand_name' => 'Logitech', 'contact_person' => 'Bracken Darrell', 'phone' => '+94771234567', 'email' => 'logitech@corp.com', 'website' => 'logitech.com'],
            ['brand_code' => 'BRD009', 'brand_name' => 'Ninja', 'contact_person' => 'Mark Barrocas', 'phone' => '+94771234568', 'email' => 'ninja@corp.com', 'website' => 'ninjakitchen.com'],
            ['brand_code' => 'BRD010', 'brand_name' => 'Breville', 'contact_person' => 'Jim Clayton', 'phone' => '+94771234569', 'email' => 'breville@corp.com', 'website' => 'breville.com'],
        ];

        $brands = [];
        foreach ($brandsData as $brd) {
            $brands[] = Brand::create(array_merge($brd, ['created_date' => Carbon::now()]));
        }

        // 6. Seed Suppliers
        $suppliers = [];
        for ($i = 1; $i <= 10; $i++) {
            $supId = 'SUP' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $suppliers[] = Supplier::create([
                'supplier_id' => $supId,
                'supplier_name' => "Supplier $i Ltd",
                'contact_person' => "Contact Person $i",
                'phone' => "+947111111" . ($i-1),
                'email' => "supplier$i@example.com",
                'address' => "No. $i, Industrial Estate, Colombo",
                'tax_id' => "TX-" . (999999 + $i),
                'payment_terms' => 'Net 30',
                'created_date' => Carbon::now(),
            ]);
        }

        // 7. Seed Branches
        $branchesData = [
            ['branch_code' => 'BRH001', 'branch_name' => 'Colombo Head Office', 'address' => 'Colombo 03', 'phone_num1' => '+94112222221', 'email' => 'colombo@nexuserp.com', 'opening_date' => Carbon::parse('2020-01-01'), 'is_active' => true],
            ['branch_code' => 'BRH002', 'branch_name' => 'Kandy Branch', 'address' => 'Kandy Town', 'phone_num1' => '+94812222222', 'email' => 'kandy@nexuserp.com', 'opening_date' => Carbon::parse('2021-06-15'), 'is_active' => true],
            ['branch_code' => 'BRH003', 'branch_name' => 'Galle Branch', 'address' => 'Galle Fort', 'phone_num1' => '+94912222223', 'email' => 'galle@nexuserp.com', 'opening_date' => Carbon::parse('2022-03-20'), 'is_active' => true],
            ['branch_code' => 'BRH004', 'branch_name' => 'Jaffna Branch', 'address' => 'Jaffna Town', 'phone_num1' => '+94212222224', 'email' => 'jaffna@nexuserp.com', 'opening_date' => Carbon::parse('2023-11-01'), 'is_active' => true],
            ['branch_code' => 'BRH005', 'branch_name' => 'Negombo Branch', 'address' => 'Negombo Beach Road', 'phone_num1' => '+94312222225', 'email' => 'negombo@nexuserp.com', 'opening_date' => Carbon::parse('2024-05-10'), 'is_active' => true],
        ];

        $branches = [];
        foreach ($branchesData as $brh) {
            $branches[] = Branch::create($brh);
        }

        // 8. Seed Departments
        $departmentsData = [
            ['department_code' => 'DEP001', 'department_name' => 'Sales & Marketing', 'description' => 'Deals with sales, client relations and marketing campaigns', 'branch_id' => 1],
            ['department_code' => 'DEP002', 'department_name' => 'Procurement & Logistics', 'description' => 'Deals with supplier sourcing, ordering and GRNs', 'branch_id' => 1],
            ['department_code' => 'DEP003', 'department_name' => 'Inventory Control', 'description' => 'Manages stocks, transactions and warehousing', 'branch_id' => 1],
            ['department_code' => 'DEP004', 'department_name' => 'Finance & Accounting', 'description' => 'Manages payroll, invoices, expenses and bank records', 'branch_id' => 1],
            ['department_code' => 'DEP005', 'department_name' => 'Human Resources', 'description' => 'Manages employee hiring, performance and details', 'branch_id' => 1],
        ];

        $departments = [];
        foreach ($departmentsData as $dep) {
            $departments[] = Department::create(array_merge($dep, ['created_date' => Carbon::now()]));
        }

        // 9. Seed Employees
        $firstNames = ['John', 'Jane', 'David', 'Sarah', 'Michael', 'Emily', 'Robert', 'Jessica', 'William', 'Ashley', 'James', 'Amanda', 'Charles', 'Mary', 'Richard'];
        $lastNames = ['Smith', 'Doe', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Wilson', 'Anderson', 'Taylor', 'Thomas', 'Moore', 'Martin', 'Jackson'];
        $positions = ['Sales Executive', 'Procurement Officer', 'Inventory Clerk', 'Accountant', 'HR Specialist', 'Branch Assistant', 'Sales Manager', 'Operations Lead', 'Office Supervisor', 'Store Manager'];

        $employees = [];
        for ($i = 0; $i < 15; $i++) {
            $code = 'EMP' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $depId = ($i % 5) + 1;
            
            $employees[] = Employee::create([
                'employee_code' => $code,
                'first_name' => $firstNames[$i],
                'last_name' => $lastNames[$i],
                'email' => strtolower($firstNames[$i]) . '.' . strtolower($lastNames[$i]) . '@nexuserp.com',
                'phone_num1' => '+947720000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'phone_num2' => '+947730000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nic_num' => '1990' . str_pad($i + 1, 8, '0', STR_PAD_LEFT) . 'V',
                'nic_front' => null,
                'nic_back' => null,
                'hire_date' => Carbon::now()->subMonths(24 - $i),
                'position' => $positions[$i % 10],
                'salary' => 45000 + ($i * 5000),
                'address' => 'No. ' . ($i + 10) . ', Main Road, Colombo',
                'date_of_birth' => Carbon::now()->subYears(22 + $i),
                'department_id' => $depId,
                'is_active' => true,
            ]);
        }

        // Update Branch Managers & Department Managers
        foreach ($branches as $index => $branch) {
            $empId = $employees[$index]->employee_id;
            $branch->update(['manager_id' => $empId]);
        }

        foreach ($departments as $index => $department) {
            $empId = $employees[$index + 5]->employee_id;
            $department->update(['manager_id' => $empId]);
        }

        // 10. Seed Stores
        $stores = [];
        for ($i = 1; $i <= 5; $i++) {
            $stores[] = Store::create([
                'store_code' => 'STR' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'store_name' => "Warehouse Section $i",
            ]);
        }

        // 11. Seed Banks
        $banksData = [
            ['bank_code' => 'BOC', 'bank_name' => 'Bank of Ceylon', 'branch_name' => 'Colombo Fort', 'account_number' => '1234567890', 'account_holder_name' => 'Nexus ERP Solutions', 'amount' => 5000000.00],
            ['bank_code' => 'COM', 'bank_name' => 'Commercial Bank', 'branch_name' => 'Kandy City', 'account_number' => '2234567891', 'account_holder_name' => 'Nexus ERP Solutions', 'amount' => 3500000.00],
            ['bank_code' => 'HNB', 'bank_name' => 'Hatton National Bank', 'branch_name' => 'Galle Fort', 'account_number' => '3234567892', 'account_holder_name' => 'Nexus ERP Solutions', 'amount' => 2500000.00],
            ['bank_code' => 'SAMP', 'bank_name' => 'Sampath Bank', 'branch_name' => 'Negombo Office', 'account_number' => '4234567893', 'account_holder_name' => 'Nexus ERP Solutions', 'amount' => 1800000.00],
            ['bank_code' => 'NDB', 'bank_name' => 'NDB Bank', 'branch_name' => 'Jaffna Office', 'account_number' => '5234567894', 'account_holder_name' => 'Nexus ERP Solutions', 'amount' => 1200000.00],
        ];

        foreach ($banksData as $bnk) {
            Bank::create(array_merge($bnk, ['created_date' => Carbon::now()]));
        }

        // 12. Seed Customers
        $customers = [];
        for ($i = 1; $i <= 15; $i++) {
            $customers[] = Customer::create([
                'customer_code' => 'CUS' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'first_name' => "Customer_First_$i",
                'last_name' => "Customer_Last_$i",
                'company_name' => $i % 3 === 0 ? "Corporate Customer $i PLC" : null,
                'email' => "customer$i@gmail.com",
                'phone' => "+947230000" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'address' => "No. $i, Galle Road, Colombo",
                'customer_type' => $i % 3 === 0 ? 'Wholesale' : 'Retail',
                'registration_date' => Carbon::now()->subMonths(12 - $i),
                'credit_limit' => $i % 3 === 0 ? 500000.00 : 0.00,
            ]);
        }

        // 13. Seed 100 Products
        $productsData = [
            // Electronics
            'Smartphones' => ['iPhone 15 Pro', 'Samsung Galaxy S24', 'Google Pixel 8', 'OnePlus 12'],
            'Laptops' => ['MacBook Air M3', 'Dell XPS 13', 'HP Spectre x360', 'Lenovo ThinkPad X1'],
            'Audio' => ['Sony WH-1000XM5', 'Bose QuietComfort Ultra', 'Apple AirPods Pro', 'Sennheiser Momentum 4'],
            'Accessories' => ['Anker PowerCore 24K', 'Logitech MX Master 3S', 'Apple Magic Keyboard', 'Samsung T7 Shield SSD'],
            'Smart Home' => ['Amazon Echo Dot', 'Google Nest Hub', 'Philips Hue Starter Kit', 'Ring Video Doorbell'],

            // Apparel
            'T-Shirts' => ['Nike Dri-FIT Tee', 'Adidas Originals Tee', 'Puma Classic Tee', 'Under Armour Sport Tee'],
            'Jeans' => ['Levis 501 Original', 'Wrangler Authentic Jeans', 'Lee Rider Slim Jeans', 'Diesel D-Strukt Jeans'],
            'Jackets' => ['Patagonia Torrentshell', 'The North Face Resolve', 'Columbia Watertight', 'Arc`teryx Beta LT'],
            'Socks' => ['Darn Tough Cushion', 'Smartwool Run Socks', 'Nike Everyday Cushion', 'Adidas Athletic Socks'],
            'Caps' => ['New Era 59FIFTY Cap', 'Nike Legacy91 Cap', 'Adidas Superlite Cap', 'Carhartt Beanie'],

            // Home & Kitchen
            'Coffee Makers' => ['Keurig K-Elite', 'Nespresso Vertuo Next', 'Breville Barista', 'Hamilton FlexBrew'],
            'Microwaves' => ['Toshiba EM131A5C', 'Panasonic NN-SN686S', 'Black+Decker EM720', 'Samsung MS19M'],
            'Blenders' => ['Vitamix 5200', 'Ninja Professional', 'NutriBullet Pro', 'Magic Bullet Blender'],
            'Toasters' => ['Breville Bit More', 'Cuisinart CPT-180', 'KitchenAid 2-Slice', 'Oster 4-Slice Toaster'],
            'Air Fryers' => ['Cosori Pro II', 'Ninja AF101', 'Instant Vortex Plus', 'Philips Essential Air'],

            // Sports
            'Footballs' => ['Wilson NFL Duke', 'Adidas Al Rihla', 'Nike Strike Ball', 'Spalding TF-1000 Ball'],
            'Dumbbells' => ['Bowflex SelectTech', 'CAP Cast Iron Dumbbell', 'Amazon Basics Neoprene', 'Rogue Rubber Hex'],
            'Yoga Mats' => ['Lululemon 5mm Mat', 'Manduka PRO Mat', 'Gaiam Essentials Mat', 'Jade Harmony Mat'],
            'Water Bottles' => ['Hydro Flask 32oz', 'Yeti Rambler 26oz', 'CamelBak Chute Mag', 'Nalgene Wide Mouth'],
            'Running Shoes' => ['Nike Air Zoom Pegasus', 'Adidas Ultraboost', 'Brooks Ghost 15', 'Asics Gel-Kayano 30'],

            // Office Supplies
            'Notebooks' => ['Moleskine Classic', 'Rhodia Webnotebook', 'Leuchtturm1917 Medium', 'Field Notes 3-Pack'],
            'Pens' => ['Pilot G2 Gel Pens', 'Uni-ball Onyx Pens', 'Parker Jotter Classic', 'Sharpie S-Gel Pens'],
            'Desk Organizers' => ['Mesh Desk Organizer', 'Wooden Desk Caddy', 'Leather Desk Pad', 'Monitor Stand Riser'],
            'Staplers' => ['Swingline 747 Classic', 'Bostitch Office Staple', 'PaperPro InSpire', 'Rapid Supreme Press'],
            'Paper Shredders' => ['Fellowes Powershred', 'Amazon Basics Shredder', 'Aurora AU800SD', 'Royal Sovereign Shred'],
        ];

        $products = [];
        $prodIndex = 1;

        foreach ($productsData as $subName => $itemsList) {
            $subModel = $subcategories[$subName];
            foreach ($itemsList as $index => $prodName) {
                $code = 'PROD' . str_pad($prodIndex, 3, '0', STR_PAD_LEFT);
                $barcode = '89010000' . str_pad($prodIndex, 4, '0', STR_PAD_LEFT);
                $brandModel = $brands[($prodIndex - 1) % 10];
                $supplierModel = $suppliers[($prodIndex - 1) % 10];
                
                // Set prices
                $costPrice = round(rand(10, 500) + (rand(1, 99)/100), 2);
                $sellingPrice = round($costPrice * 1.35, 2); // 35% margin
                
                $products[] = Product::create([
                    'product_name' => $prodName,
                    'product_code' => $code,
                    'brand_id' => $brandModel->brand_id,
                    'subcategory_id' => $subModel->subcategory_id,
                    'price' => $sellingPrice,
                    'unit' => $prodIndex % 10 === 0 ? 'box' : ($prodIndex % 15 === 0 ? 'kg' : 'pcs'),
                    'status' => 'active',
                    'description' => "Premium quality $prodName $subName from $brandModel->brand_name.",
                    'user_id' => $admin->id,
                    'barcode' => $barcode,
                    'cost_price' => $costPrice,
                    'weight' => rand(1, 10) . ' kg',
                    'dimensions' => rand(5, 50) . 'x' . rand(5, 50) . 'x' . rand(5, 50) . ' cm',
                    'supplier_id' => $supplierModel->supplier_id,
                ]);

                $prodIndex++;
            }
        }

        // 14. Seed Stocks
        $stocks = [];
        foreach ($products as $product) {
            // Seed stock at 3 branches (1, 2, 3)
            for ($branchId = 1; $branchId <= 3; $branchId++) {
                $sub = SubCategory::find($product->subcategory_id);
                $stocks[] = Stock::create([
                    'product_id' => $product->product_id,
                    'branch_id' => $branchId,
                    'brand_id' => $product->brand_id,
                    'category_id' => $sub->category_id,
                    'subcategory_id' => $product->subcategory_id,
                    'product_name' => $product->product_name,
                    'quantity' => rand(20, 150),
                    'minimum_stock_level' => 15,
                    'maximum_stock_level' => 400,
                    'last_restocked_date' => Carbon::now()->subDays(rand(1, 30)),
                    'location_in_warehouse' => 'Aisle ' . chr(rand(65, 70)) . '-' . rand(1, 10),
                ]);
            }
        }

        // 15. Seed Stock Transactions (Initial stock receipt & audits)
        foreach ($stocks as $index => $stk) {
            if ($index % 3 === 0) {
                StockTransaction::create([
                    'stock_id' => $stk->stock_id,
                    'product_id' => $stk->product_id,
                    'branch_id' => $stk->branch_id,
                    'brand_id' => $stk->brand_id,
                    'category_id' => $stk->category_id,
                    'subcategory_id' => $stk->subcategory_id,
                    'product_name' => $stk->product_name,
                    'transaction_type' => 'initial',
                    'quantity_before' => 0,
                    'quantity_changed' => $stk->quantity,
                    'quantity_after' => $stk->quantity,
                    'reference_number' => 'INIT-STK-' . $stk->stock_id,
                    'notes' => 'Initial stock setup via seeder',
                    'created_by' => $admin->id,
                ]);
            }
        }

        // 16. Seed Purchases (GRNs)
        for ($p = 1; $p <= 10; $p++) {
            $grnNo = 'GRN-' . Carbon::now()->year . '-' . str_pad($p, 4, '0', STR_PAD_LEFT);
            $supplier = $suppliers[$p - 1];
            $branch = $branches[($p - 1) % 5];
            
            // Generate purchase records with items
            $purchase = Purchase::create([
                'purchase_id' => $grnNo,
                'supplier_id' => $supplier->supplier_id,
                'branch_id' => $branch->branch_id,
                'purchase_date' => Carbon::now()->subDays(15 - $p),
                'reference_no' => 'REF-' . rand(10000, 99999),
                'subtotal' => 0, // Calculate below
                'tax_amount' => 0,
                'discount' => 0,
                'grand_total' => 0,
                'payment_method' => $p % 2 === 0 ? 'Bank Transfer' : 'Cash',
                'amount_paid' => 0,
                'balance_due' => 0,
                'payment_notes' => 'Paid in full',
                'notes' => "Purchase record $p via seeder",
                'bank_name' => $p % 2 === 0 ? 'BOC' : null,
                'bank_reference' => $p % 2 === 0 ? 'TXN' . rand(100000, 999999) : null,
            ]);

            $subtotal = 0;
            // Add 3-5 random items to this GRN
            $numItems = rand(3, 5);
            $selectedProds = array_slice($products, ($p - 1) * 5, $numItems);
            
            foreach ($selectedProds as $prod) {
                $qty = rand(10, 30);
                $itemSubtotal = $prod->cost_price * $qty;
                $subtotal += $itemSubtotal;

                PurchaseItem::create([
                    'purchase_record_id' => $purchase->id,
                    'purchase_id' => $grnNo,
                    'product_id' => $prod->product_id,
                    'product_name' => $prod->product_name,
                    'product_code' => $prod->product_code,
                    'quantity' => $qty,
                    'stock_price' => $prod->cost_price,
                    'selling_price' => $prod->price,
                    'discount' => 0,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            $tax = round($subtotal * 0.08, 2); // 8% tax
            $discount = $p % 3 === 0 ? 50.00 : 0.00;
            $grandTotal = $subtotal + $tax - $discount;

            $purchase->update([
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'amount_paid' => $grandTotal,
                'balance_due' => 0,
            ]);
        }

        // 17. Seed Orders
        for ($o = 1; $o <= 15; $o++) {
            $customer = $customers[$o - 1];
            $branch = $branches[($o - 1) % 5];
            $employee = $employees[($o - 1) % 15];
            $ordNo = 'ORD-' . Carbon::now()->year . '-' . str_pad($o, 4, '0', STR_PAD_LEFT);
            $totalAmt = rand(100, 1500);

            Order::create([
                'order_number' => $ordNo,
                'order_date' => Carbon::now()->subDays(20 - $o),
                'customer_id' => $customer->customer_id,
                'branch_id' => $branch->branch_id,
                'employee_id' => $employee->employee_id,
                'total_amount' => $totalAmt,
                'status' => $o % 4 === 0 ? 'Pending' : ($o % 5 === 0 ? 'Cancelled' : 'Completed'),
                'payment_status' => $o % 4 === 0 ? 'Unpaid' : 'Paid',
                'shipping_address' => $customer->address,
                'notes' => "Sample order $o notes",
            ]);
        }

        // 18. Seed Invoices (Sales)
        for ($inv = 1; $inv <= 20; $inv++) {
            $invNo = 'INV-' . Carbon::now()->year . '-' . str_pad($inv, 4, '0', STR_PAD_LEFT);
            
            $invoice = Invoice::create([
                'invoice_number' => $invNo,
                'net_total' => 0, // Calculate below
                'payment_method' => $inv % 3 === 0 ? 'card' : 'cash',
                'cash_given' => 0,
                'balance_returned' => 0,
                'status' => 'paid',
                'created_at' => Carbon::now()->subDays(30 - $inv)->subHours(rand(1, 12)),
            ]);

            $total = 0;
            // Add 2-4 items
            $numItems = rand(2, 4);
            $selectedProds = array_slice($products, ($inv - 1) * 4, $numItems);

            foreach ($selectedProds as $prod) {
                $qty = rand(1, 5);
                $itemTotal = $prod->price * $qty;
                $total += $itemTotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->invoice_id,
                    'product_id' => $prod->product_id,
                    'product_name' => $prod->product_name,
                    'product_code' => $prod->product_code,
                    'price' => $prod->price,
                    'qty' => $qty,
                    'total' => $itemTotal,
                ]);
            }

            $cashGiven = ceil($total / 50) * 50; // Round up to nearest 50
            $balReturned = $cashGiven - $total;

            $invoice->update([
                'net_total' => $total,
                'cash_given' => $invoice->payment_method === 'cash' ? $cashGiven : $total,
                'balance_returned' => $invoice->payment_method === 'cash' ? $balReturned : 0,
            ]);
        }
    }
}
