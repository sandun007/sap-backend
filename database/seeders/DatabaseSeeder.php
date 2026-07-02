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
        \App\Models\ProductPriceHistory::truncate();
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
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'SAP',
            'email' => 'admin@sap.com',
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

        // 13. Seed 100 Products with realistic brands and Rupee prices
        $productsData = [
            // Smartphones
            ['name' => 'iPhone 15 Pro', 'subcategory' => 'Smartphones', 'brand' => 'Apple', 'cost' => 280000.00, 'price' => 350000.00],
            ['name' => 'Samsung Galaxy S24', 'subcategory' => 'Smartphones', 'brand' => 'Samsung', 'cost' => 240000.00, 'price' => 310000.00],
            ['name' => 'Google Pixel 8', 'subcategory' => 'Smartphones', 'brand' => 'Google', 'cost' => 180000.00, 'price' => 230000.00],
            ['name' => 'OnePlus 12', 'subcategory' => 'Smartphones', 'brand' => 'OnePlus', 'cost' => 190000.00, 'price' => 240000.00],

            // Laptops
            ['name' => 'MacBook Air M3', 'subcategory' => 'Laptops', 'brand' => 'Apple', 'cost' => 290000.00, 'price' => 370000.00],
            ['name' => 'Dell XPS 13', 'subcategory' => 'Laptops', 'brand' => 'Dell', 'cost' => 310000.00, 'price' => 390000.00],
            ['name' => 'HP Spectre x360', 'subcategory' => 'Laptops', 'brand' => 'HP', 'cost' => 320000.00, 'price' => 410000.00],
            ['name' => 'Lenovo ThinkPad X1', 'subcategory' => 'Laptops', 'brand' => 'Lenovo', 'cost' => 340000.00, 'price' => 430000.00],

            // Audio
            ['name' => 'Sony WH-1000XM5', 'subcategory' => 'Audio', 'brand' => 'Sony', 'cost' => 85000.00, 'price' => 110000.00],
            ['name' => 'Bose QuietComfort Ultra', 'subcategory' => 'Audio', 'brand' => 'Bose', 'cost' => 90000.00, 'price' => 120000.00],
            ['name' => 'Apple AirPods Pro', 'subcategory' => 'Audio', 'brand' => 'Apple', 'cost' => 60000.00, 'price' => 80000.00],
            ['name' => 'Sennheiser Momentum 4', 'subcategory' => 'Audio', 'brand' => 'Sennheiser', 'cost' => 75000.00, 'price' => 95000.00],

            // Accessories
            ['name' => 'Anker PowerCore 24K', 'subcategory' => 'Accessories', 'brand' => 'Anker', 'cost' => 15000.00, 'price' => 20000.00],
            ['name' => 'Logitech MX Master 3S', 'subcategory' => 'Accessories', 'brand' => 'Logitech', 'cost' => 25000.00, 'price' => 32000.00],
            ['name' => 'Apple Magic Keyboard', 'subcategory' => 'Accessories', 'brand' => 'Apple', 'cost' => 30000.00, 'price' => 40000.00],
            ['name' => 'Samsung T7 Shield SSD', 'subcategory' => 'Accessories', 'brand' => 'Samsung', 'cost' => 35000.00, 'price' => 45000.00],

            // Smart Home
            ['name' => 'Amazon Echo Dot', 'subcategory' => 'Smart Home', 'brand' => 'Amazon', 'cost' => 12000.00, 'price' => 16000.00],
            ['name' => 'Google Nest Hub', 'subcategory' => 'Smart Home', 'brand' => 'Google', 'cost' => 25000.00, 'price' => 32000.00],
            ['name' => 'Philips Hue Starter Kit', 'subcategory' => 'Smart Home', 'brand' => 'Philips', 'cost' => 35000.00, 'price' => 45000.00],
            ['name' => 'Ring Video Doorbell', 'subcategory' => 'Smart Home', 'brand' => 'Ring', 'cost' => 45000.00, 'price' => 60000.00],

            // T-Shirts
            ['name' => 'Nike Dri-FIT Tee', 'subcategory' => 'T-Shirts', 'brand' => 'Nike', 'cost' => 4500.00, 'price' => 6000.00],
            ['name' => 'Adidas Originals Tee', 'subcategory' => 'T-Shirts', 'brand' => 'Adidas', 'cost' => 5000.00, 'price' => 6500.00],
            ['name' => 'Puma Classic Tee', 'subcategory' => 'T-Shirts', 'brand' => 'Puma', 'cost' => 3800.00, 'price' => 5000.00],
            ['name' => 'Under Armour Sport Tee', 'subcategory' => 'T-Shirts', 'brand' => 'Under Armour', 'cost' => 4200.00, 'price' => 5500.00],

            // Jeans
            ['name' => 'Levis 501 Original', 'subcategory' => 'Jeans', 'brand' => 'Levi\'s', 'cost' => 12000.00, 'price' => 16500.00],
            ['name' => 'Wrangler Authentic Jeans', 'subcategory' => 'Jeans', 'brand' => 'Wrangler', 'cost' => 8500.00, 'price' => 11500.00],
            ['name' => 'Lee Rider Slim Jeans', 'subcategory' => 'Jeans', 'brand' => 'Lee', 'cost' => 9000.00, 'price' => 12500.00],
            ['name' => 'Diesel D-Strukt Jeans', 'subcategory' => 'Jeans', 'brand' => 'Diesel', 'cost' => 25000.00, 'price' => 35000.00],

            // Jackets
            ['name' => 'Patagonia Torrentshell', 'subcategory' => 'Jackets', 'brand' => 'Patagonia', 'cost' => 35000.00, 'price' => 48000.00],
            ['name' => 'The North Face Resolve', 'subcategory' => 'Jackets', 'brand' => 'The North Face', 'cost' => 28000.00, 'price' => 38000.00],
            ['name' => 'Columbia Watertight', 'subcategory' => 'Jackets', 'brand' => 'Columbia', 'cost' => 22000.00, 'price' => 30000.00],
            ['name' => 'Arc`teryx Beta LT', 'subcategory' => 'Jackets', 'brand' => 'Arc\'teryx', 'cost' => 95000.00, 'price' => 130000.00],

            // Socks
            ['name' => 'Darn Tough Cushion', 'subcategory' => 'Socks', 'brand' => 'Darn Tough', 'cost' => 4000.00, 'price' => 5500.00],
            ['name' => 'Smartwool Run Socks', 'subcategory' => 'Socks', 'brand' => 'Smartwool', 'cost' => 3500.00, 'price' => 4800.00],
            ['name' => 'Nike Everyday Cushion', 'subcategory' => 'Socks', 'brand' => 'Nike', 'cost' => 2200.00, 'price' => 3000.00],
            ['name' => 'Adidas Athletic Socks', 'subcategory' => 'Socks', 'brand' => 'Adidas', 'cost' => 2000.00, 'price' => 2800.00],

            // Caps
            ['name' => 'New Era 59FIFTY Cap', 'subcategory' => 'Caps', 'brand' => 'New Era', 'cost' => 6500.00, 'price' => 9000.00],
            ['name' => 'Nike Legacy91 Cap', 'subcategory' => 'Caps', 'brand' => 'Nike', 'cost' => 4500.00, 'price' => 6000.00],
            ['name' => 'Adidas Superlite Cap', 'subcategory' => 'Caps', 'brand' => 'Adidas', 'cost' => 3800.00, 'price' => 5200.00],
            ['name' => 'Carhartt Beanie', 'subcategory' => 'Caps', 'brand' => 'Carhartt', 'cost' => 4000.00, 'price' => 5500.00],

            // Coffee Makers
            ['name' => 'Keurig K-Elite', 'subcategory' => 'Coffee Makers', 'brand' => 'Keurig', 'cost' => 45000.00, 'price' => 60000.00],
            ['name' => 'Nespresso Vertuo Next', 'subcategory' => 'Coffee Makers', 'brand' => 'Nespresso', 'cost' => 55000.00, 'price' => 75000.00],
            ['name' => 'Breville Barista', 'subcategory' => 'Coffee Makers', 'brand' => 'Breville', 'cost' => 180000.00, 'price' => 240000.00],
            ['name' => 'Hamilton FlexBrew', 'subcategory' => 'Coffee Makers', 'brand' => 'Hamilton Beach', 'cost' => 32000.00, 'price' => 44000.00],

            // Microwaves
            ['name' => 'Toshiba EM131A5C', 'subcategory' => 'Microwaves', 'brand' => 'Toshiba', 'cost' => 35000.00, 'price' => 48000.00],
            ['name' => 'Panasonic NN-SN686S', 'subcategory' => 'Microwaves', 'brand' => 'Panasonic', 'cost' => 48000.00, 'price' => 65000.00],
            ['name' => 'Black+Decker EM720', 'subcategory' => 'Microwaves', 'brand' => 'Black+Decker', 'cost' => 25000.00, 'price' => 34000.00],
            ['name' => 'Samsung MS19M', 'subcategory' => 'Microwaves', 'brand' => 'Samsung', 'cost' => 42000.00, 'price' => 58000.00],

            // Blenders
            ['name' => 'Vitamix 5200', 'subcategory' => 'Blenders', 'brand' => 'Vitamix', 'cost' => 110000.00, 'price' => 150000.00],
            ['name' => 'Ninja Professional', 'subcategory' => 'Blenders', 'brand' => 'Ninja', 'cost' => 38000.00, 'price' => 52000.00],
            ['name' => 'NutriBullet Pro', 'subcategory' => 'Blenders', 'brand' => 'NutriBullet', 'cost' => 28000.00, 'price' => 38000.00],
            ['name' => 'Magic Bullet Blender', 'subcategory' => 'Blenders', 'brand' => 'Magic Bullet', 'cost' => 12000.00, 'price' => 16500.00],

            // Toasters
            ['name' => 'Breville Bit More', 'subcategory' => 'Toasters', 'brand' => 'Breville', 'cost' => 22000.00, 'price' => 30000.00],
            ['name' => 'Cuisinart CPT-180', 'subcategory' => 'Toasters', 'brand' => 'Cuisinart', 'cost' => 18000.00, 'price' => 25000.00],
            ['name' => 'KitchenAid 2-Slice', 'subcategory' => 'Toasters', 'brand' => 'KitchenAid', 'cost' => 25000.00, 'price' => 34000.00],
            ['name' => 'Oster 4-Slice Toaster', 'subcategory' => 'Toasters', 'brand' => 'Oster', 'cost' => 15000.00, 'price' => 20000.00],

            // Air Fryers
            ['name' => 'Cosori Pro II', 'subcategory' => 'Air Fryers', 'brand' => 'Cosori', 'cost' => 32000.00, 'price' => 44000.00],
            ['name' => 'Ninja AF101', 'subcategory' => 'Air Fryers', 'brand' => 'Ninja', 'cost' => 38000.00, 'price' => 52000.00],
            ['name' => 'Instant Vortex Plus', 'subcategory' => 'Air Fryers', 'brand' => 'Instant Pot', 'cost' => 42000.00, 'price' => 58000.00],
            ['name' => 'Philips Essential Air', 'subcategory' => 'Air Fryers', 'brand' => 'Philips', 'cost' => 35000.00, 'price' => 48000.00],

            // Footballs
            ['name' => 'Wilson NFL Duke', 'subcategory' => 'Footballs', 'brand' => 'Wilson', 'cost' => 12000.00, 'price' => 16500.00],
            ['name' => 'Adidas Al Rihla', 'subcategory' => 'Footballs', 'brand' => 'Adidas', 'cost' => 15000.00, 'price' => 20000.00],
            ['name' => 'Nike Strike Ball', 'subcategory' => 'Footballs', 'brand' => 'Nike', 'cost' => 6500.00, 'price' => 9000.00],
            ['name' => 'Spalding TF-1000 Ball', 'subcategory' => 'Footballs', 'brand' => 'Spalding', 'cost' => 18000.00, 'price' => 24000.00],

            // Dumbbells
            ['name' => 'Bowflex SelectTech', 'subcategory' => 'Dumbbells', 'brand' => 'Bowflex', 'cost' => 95000.00, 'price' => 130000.00],
            ['name' => 'CAP Cast Iron Dumbbell', 'subcategory' => 'Dumbbells', 'brand' => 'CAP', 'cost' => 8000.00, 'price' => 11000.00],
            ['name' => 'Amazon Basics Neoprene', 'subcategory' => 'Dumbbells', 'brand' => 'Amazon Basics', 'cost' => 4500.00, 'price' => 6000.00],
            ['name' => 'Rogue Rubber Hex', 'subcategory' => 'Dumbbells', 'brand' => 'Rogue', 'cost' => 12000.00, 'price' => 16500.00],

            // Yoga Mats
            ['name' => 'Lululemon 5mm Mat', 'subcategory' => 'Yoga Mats', 'brand' => 'Lululemon', 'cost' => 18000.00, 'price' => 25000.00],
            ['name' => 'Manduka PRO Mat', 'subcategory' => 'Yoga Mats', 'brand' => 'Manduka', 'cost' => 22000.00, 'price' => 30000.00],
            ['name' => 'Gaiam Essentials Mat', 'subcategory' => 'Yoga Mats', 'brand' => 'Gaiam', 'cost' => 6500.00, 'price' => 9000.00],
            ['name' => 'Jade Harmony Mat', 'subcategory' => 'Yoga Mats', 'brand' => 'Jade', 'cost' => 15000.00, 'price' => 21000.00],

            // Water Bottles
            ['name' => 'Hydro Flask 32oz', 'subcategory' => 'Water Bottles', 'brand' => 'Hydro Flask', 'cost' => 12000.00, 'price' => 16500.00],
            ['name' => 'Yeti Rambler 26oz', 'subcategory' => 'Water Bottles', 'brand' => 'Yeti', 'cost' => 11000.00, 'price' => 15000.00],
            ['name' => 'CamelBak Chute Mag', 'subcategory' => 'Water Bottles', 'brand' => 'CamelBak', 'cost' => 6500.00, 'price' => 9000.00],
            ['name' => 'Nalgene Wide Mouth', 'subcategory' => 'Water Bottles', 'brand' => 'Nalgene', 'cost' => 4500.00, 'price' => 6000.00],

            // Running Shoes
            ['name' => 'Nike Air Zoom Pegasus', 'subcategory' => 'Running Shoes', 'brand' => 'Nike', 'cost' => 32000.00, 'price' => 44000.00],
            ['name' => 'Adidas Ultraboost', 'subcategory' => 'Running Shoes', 'brand' => 'Adidas', 'cost' => 42000.00, 'price' => 58000.00],
            ['name' => 'Brooks Ghost 15', 'subcategory' => 'Running Shoes', 'brand' => 'Brooks', 'cost' => 30000.00, 'price' => 42000.00],
            ['name' => 'Asics Gel-Kayano 30', 'subcategory' => 'Running Shoes', 'brand' => 'Asics', 'cost' => 38000.00, 'price' => 52000.00],

            // Notebooks
            ['name' => 'Moleskine Classic', 'subcategory' => 'Notebooks', 'brand' => 'Moleskine', 'cost' => 4500.00, 'price' => 6000.00],
            ['name' => 'Rhodia Webnotebook', 'subcategory' => 'Notebooks', 'brand' => 'Rhodia', 'cost' => 3800.00, 'price' => 5200.00],
            ['name' => 'Leuchtturm1917 Medium', 'subcategory' => 'Notebooks', 'brand' => 'Leuchtturm1917', 'cost' => 4200.00, 'price' => 5800.00],
            ['name' => 'Field Notes 3-Pack', 'subcategory' => 'Notebooks', 'brand' => 'Field Notes', 'cost' => 2200.00, 'price' => 3000.00],

            // Pens
            ['name' => 'Pilot G2 Gel Pens', 'subcategory' => 'Pens', 'brand' => 'Pilot', 'cost' => 1200.00, 'price' => 1650.00],
            ['name' => 'Uni-ball Onyx Pens', 'subcategory' => 'Pens', 'brand' => 'Uni-ball', 'cost' => 800.00, 'price' => 1100.00],
            ['name' => 'Parker Jotter Classic', 'subcategory' => 'Pens', 'brand' => 'Parker', 'cost' => 3500.00, 'price' => 4800.00],
            ['name' => 'Sharpie S-Gel Pens', 'subcategory' => 'Pens', 'brand' => 'Sharpie', 'cost' => 1500.00, 'price' => 2000.00],

            // Desk Organizers
            ['name' => 'Mesh Desk Organizer', 'subcategory' => 'Desk Organizers', 'brand' => 'Mesh', 'cost' => 2500.00, 'price' => 3500.00],
            ['name' => 'Wooden Desk Caddy', 'subcategory' => 'Desk Organizers', 'brand' => 'Wooden', 'cost' => 3800.00, 'price' => 5200.00],
            ['name' => 'Leather Desk Pad', 'subcategory' => 'Desk Organizers', 'brand' => 'Leather', 'cost' => 6500.00, 'price' => 9000.00],
            ['name' => 'Monitor Stand Riser', 'subcategory' => 'Desk Organizers', 'brand' => 'Monitor Stand', 'cost' => 5000.00, 'price' => 7000.00],

            // Staplers
            ['name' => 'Swingline 747 Classic', 'subcategory' => 'Staplers', 'brand' => 'Swingline', 'cost' => 4500.00, 'price' => 6000.00],
            ['name' => 'Bostitch Office Staple', 'subcategory' => 'Staplers', 'brand' => 'Bostitch', 'cost' => 3500.00, 'price' => 4800.00],
            ['name' => 'PaperPro InSpire', 'subcategory' => 'Staplers', 'brand' => 'PaperPro', 'cost' => 2800.00, 'price' => 3800.00],
            ['name' => 'Rapid Supreme Press', 'subcategory' => 'Staplers', 'brand' => 'Rapid', 'cost' => 5000.00, 'price' => 6800.00],

            // Paper Shredders
            ['name' => 'Fellowes Powershred', 'subcategory' => 'Paper Shredders', 'brand' => 'Fellowes', 'cost' => 28000.00, 'price' => 38000.00],
            ['name' => 'Amazon Basics Shredder', 'subcategory' => 'Paper Shredders', 'brand' => 'Amazon Basics', 'cost' => 15000.00, 'price' => 20000.00],
            ['name' => 'Aurora AU800SD', 'subcategory' => 'Paper Shredders', 'brand' => 'Aurora', 'cost' => 18000.00, 'price' => 25000.00],
            ['name' => 'Royal Sovereign Shred', 'subcategory' => 'Paper Shredders', 'brand' => 'Royal Sovereign', 'cost' => 22000.00, 'price' => 30000.00]
        ];

        $products = [];
        $prodIndex = 1;
        $brandCache = [];

        foreach ($productsData as $item) {
            $prodName = $item['name'];
            $brandName = $item['brand'];
            $origPrice = $item['price'];
            if ($origPrice >= 50000) {
                $sellingPrice = 10000.00;
                $costPrice = 7000.00;
            } elseif ($origPrice >= 5000) {
                $sellingPrice = 1000.00;
                $costPrice = 700.00;
            } else {
                $sellingPrice = 100.00;
                $costPrice = 70.00;
            }
            $subName = $item['subcategory'];

            $subModel = $subcategories[$subName];
            $supplierModel = $suppliers[($prodIndex - 1) % 10];

            if (!isset($brandCache[$brandName])) {
                $brandCache[$brandName] = Brand::firstOrCreate(
                    ['brand_name' => $brandName],
                    [
                        'brand_code' => 'BRD' . str_pad(count($brandCache) + 11, 3, '0', STR_PAD_LEFT),
                        'contact_person' => 'Contact ' . $brandName,
                        'phone' => '+94771234567',
                        'email' => strtolower(str_replace(' ', '', $brandName)) . '@corp.com',
                        'website' => strtolower(str_replace(' ', '', $brandName)) . '.com',
                        'created_date' => Carbon::now()
                    ]
                );
            }
            $brandModel = $brandCache[$brandName];

            $code = 'PROD' . str_pad($prodIndex, 3, '0', STR_PAD_LEFT);
            $barcode = '89010000' . str_pad($prodIndex, 4, '0', STR_PAD_LEFT);

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

        // Top up all brands to have at least 50 products
        $subNames = [
            1 => 'Smartphones', 2 => 'Laptops', 3 => 'Audio', 4 => 'Accessories', 5 => 'Smart Home',
            6 => 'T-Shirts', 7 => 'Jeans', 8 => 'Jackets', 9 => 'Socks', 10 => 'Caps',
            11 => 'Coffee Makers', 12 => 'Microwaves', 13 => 'Blenders', 14 => 'Toasters', 15 => 'Air Fryers',
            16 => 'Footballs', 17 => 'Dumbbells', 18 => 'Yoga Mats', 19 => 'Water Bottles', 20 => 'Running Shoes',
            21 => 'Notebooks', 22 => 'Pens', 23 => 'Desk Organizers', 24 => 'Staplers', 25 => 'Paper Shredders',
        ];

        DB::beginTransaction();

        $allBrands = Brand::all();
        foreach ($allBrands as $brand) {
            $currentCount = 0;
            foreach ($products as $p) {
                if ((int)$p->brand_id === (int)$brand->brand_id) {
                    $currentCount++;
                }
            }

            $needed = 50 - $currentCount;
            for ($i = 1; $i <= $needed; $i++) {
                $subId = (($prodIndex - 1) % 25) + 1;
                $subName = $subNames[$subId];
                
                $prodName = $this->getRealisticProductName($brand->brand_name, $subName, $i);
                $code = 'PROD' . str_pad($prodIndex, 4, '0', STR_PAD_LEFT);
                $barcode = '89010000' . str_pad($prodIndex, 5, '0', STR_PAD_LEFT);

                $supplierModel = $suppliers[($prodIndex - 1) % 10];

                $costPrice = rand(100, 5000);
                $sellingPrice = round($costPrice * 1.3, -2); // 30% margin, rounded to nearest 100

                $newProduct = Product::create([
                    'product_name' => $prodName,
                    'product_code' => $code,
                    'brand_id' => $brand->brand_id,
                    'subcategory_id' => $subId,
                    'price' => $sellingPrice,
                    'unit' => $prodIndex % 10 === 0 ? 'box' : ($prodIndex % 15 === 0 ? 'kg' : 'pcs'),
                    'status' => 'active',
                    'description' => "Premium quality $prodName from $brand->brand_name.",
                    'user_id' => $admin->id,
                    'barcode' => $barcode,
                    'cost_price' => $costPrice,
                    'weight' => rand(1, 10) . ' kg',
                    'dimensions' => rand(5, 50) . 'x' . rand(5, 50) . 'x' . rand(5, 50) . ' cm',
                    'supplier_id' => $supplierModel->supplier_id,
                ]);

                \App\Models\ProductPriceHistory::create([
                    'product_id' => $newProduct->product_id,
                    'old_cost_price' => 0.00,
                    'new_cost_price' => $newProduct->cost_price,
                    'old_selling_price' => 0.00,
                    'new_selling_price' => $newProduct->price,
                    'user_id' => $admin->id
                ]);

                $products[] = $newProduct;
                $prodIndex++;
            }
        }

        DB::commit();

        // 14. Seed Stocks
        $stocks = [];
        $subcategoriesList = SubCategory::all()->keyBy('subcategory_id');

        DB::beginTransaction();

        foreach ($products as $product) {
            // Seed stock at 3 branches (1, 2, 3)
            for ($branchId = 1; $branchId <= 3; $branchId++) {
                $sub = $subcategoriesList[$product->subcategory_id];
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

        DB::commit();

        // 16. Seed Purchases (GRNs) distributed over 5 months
        $pIndex = 1;
        for ($m = 4; $m >= 0; $m--) {
            for ($i = 1; $i <= 3; $i++) {
                $targetMonth = Carbon::now()->subMonths($m);
                if ($m === 0) {
                    $daysLimit = Carbon::now()->day;
                    $randDay = rand(0, $daysLimit - 1);
                    $grnDate = Carbon::now()->startOfMonth()->addDays($randDay)->subHours(rand(1, 12));
                } else {
                    $daysInMonth = $targetMonth->daysInMonth;
                    $randDay = rand(0, $daysInMonth - 1);
                    $grnDate = $targetMonth->copy()->startOfMonth()->addDays($randDay)->subHours(rand(1, 12));
                }
                $grnNo = 'GRN-' . $grnDate->year . '-' . str_pad($pIndex, 4, '0', STR_PAD_LEFT);
                $supplier = $suppliers[($pIndex - 1) % 10];
                $branch = $branches[($pIndex - 1) % 5];
                
                $purchase = Purchase::create([
                    'purchase_id' => $grnNo,
                    'supplier_id' => $supplier->supplier_id,
                    'branch_id' => $branch->branch_id,
                    'purchase_date' => $grnDate->toDateString(),
                    'created_at' => $grnDate,
                    'updated_at' => $grnDate,
                    'reference_no' => 'REF-' . rand(10000, 99999),
                    'subtotal' => 0, // Calculate below
                    'tax_amount' => 0,
                    'discount' => 0,
                    'grand_total' => 0,
                    'payment_method' => $pIndex % 2 === 0 ? 'Bank Transfer' : 'Cash',
                    'amount_paid' => 0,
                    'balance_due' => 0,
                    'payment_notes' => 'Paid in full',
                    'notes' => "Purchase record $pIndex via seeder",
                    'bank_name' => $pIndex % 2 === 0 ? 'BOC' : null,
                    'bank_reference' => $pIndex % 2 === 0 ? 'TXN' . rand(100000, 999999) : null,
                ]);

                $subtotal = 0;
                // Add 3-5 items to this GRN
                $numItems = rand(3, 5);
                $selectedProds = array_slice($products, (($pIndex - 1) * 3) % (count($products) - 5), $numItems);
                
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
                        'created_at' => $grnDate,
                        'updated_at' => $grnDate,
                    ]);
                }

                $tax = round($subtotal * 0.08, 2); // 8% tax
                $discount = $pIndex % 3 === 0 ? 50.00 : 0.00;
                $grandTotal = $subtotal + $tax - $discount;

                $purchase->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $tax,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'amount_paid' => $grandTotal,
                    'balance_due' => 0,
                ]);

                $pIndex++;
            }
        }

        // 17. Seed Orders distributed over last 20 days
        for ($o = 1; $o <= 25; $o++) {
            $customer = $customers[($o - 1) % 15];
            $branch = $branches[($o - 1) % 5];
            $employee = $employees[($o - 1) % 15];
            
            $orderDate = Carbon::now()->subDays(20 - $o)->subHours(rand(1, 12));
            $ordNo = 'ORD-' . $orderDate->year . '-' . str_pad($o, 4, '0', STR_PAD_LEFT);
            $totalAmt = rand(1000, 15000);

            Order::create([
                'order_number' => $ordNo,
                'order_date' => $orderDate->toDateString(),
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
                'customer_id' => $customer->customer_id,
                'branch_id' => $branch->branch_id,
                'employee_id' => $employee->employee_id,
                'total_amount' => $totalAmt,
                'status' => $o % 6 === 0 ? 'Pending' : ($o % 7 === 0 ? 'Cancelled' : 'Completed'),
                'payment_status' => $o % 6 === 0 ? 'Unpaid' : 'Paid',
                'shipping_address' => $customer->address,
                'notes' => "Sample order $o notes",
            ]);
        }

        // 18. Seed Invoices (Sales) distributed over 5 months
        $invIndex = 1;
        for ($m = 4; $m >= 0; $m--) {
            for ($i = 1; $i <= 16; $i++) {
                $targetMonth = Carbon::now()->subMonths($m);
                if ($m === 0) {
                    $daysLimit = Carbon::now()->day;
                    $randDay = rand(0, $daysLimit - 1);
                    $invDate = Carbon::now()->startOfMonth()->addDays($randDay)->subHours(rand(1, 12));
                } else {
                    $daysInMonth = $targetMonth->daysInMonth;
                    $randDay = rand(0, $daysInMonth - 1);
                    $invDate = $targetMonth->copy()->startOfMonth()->addDays($randDay)->subHours(rand(1, 12));
                }
                $invNo = 'INV-' . $invDate->year . '-' . str_pad($invIndex, 4, '0', STR_PAD_LEFT);
                
                $invoice = Invoice::create([
                    'invoice_number' => $invNo,
                    'branch_id' => ($invIndex % 3) + 1,
                    'net_total' => 0, // Calculate below
                    'payment_method' => $invIndex % 3 === 0 ? 'card' : 'cash',
                    'cash_given' => 0,
                    'balance_returned' => 0,
                    'status' => 'paid',
                    'created_at' => $invDate,
                    'updated_at' => $invDate,
                ]);

                $total = 0;
                // Add 2-4 items
                $numItems = rand(2, 4);
                $selectedProds = array_slice($products, (($invIndex - 1) * 2) % (count($products) - 4), $numItems);

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
                        'created_at' => $invDate,
                        'updated_at' => $invDate,
                    ]);
                }

                $cashGiven = ceil($total / 100) * 100; // Round up to nearest 100
                $balReturned = $cashGiven - $total;

                $invoice->update([
                    'net_total' => $total,
                    'cash_given' => $invoice->payment_method === 'cash' ? $cashGiven : $total,
                    'balance_returned' => $invoice->payment_method === 'cash' ? $balReturned : 0,
                ]);

                $invIndex++;
            }
        }

        // 19. Seed Stock Adjustments (for Stock Adjustment Report)
        $adjustmentNotes = [
            'Physical count discrepancy resolved',
            'Damaged packaging - stock removed',
            'Found extra stock during audit',
            'Expired items discarded',
            'Data entry error correction',
            'Returned by delivery agent due to slight damage',
            'System syncing discrepancy adjustment'
        ];

        // Create 20 random stock adjustments
        for ($k = 1; $k <= 20; $k++) {
            $stk = $stocks[array_rand($stocks)];
            $quantityBefore = $stk->quantity;
            
            // Random discrepancy (positive or negative) between -10 and 15, but not 0
            $discrepancy = 0;
            while ($discrepancy === 0) {
                $discrepancy = rand(-10, 15);
            }
            
            // Avoid negative stock quantities
            if ($quantityBefore + $discrepancy < 0) {
                $discrepancy = rand(1, 10);
            }
            
            $quantityAfter = $quantityBefore + $discrepancy;
            
            // Update stock quantity to reflect adjustment
            $stk->update([
                'quantity' => $quantityAfter
            ]);

            // Pick a random GRN number for batch adjustments or a normal note
            $note = $adjustmentNotes[array_rand($adjustmentNotes)];
            if ($k % 2 === 0) {
                $randomGrn = 'GRN-2026-' . str_pad(rand(1, 10), 4, '0', STR_PAD_LEFT);
                $note = "Batch Adj [GRN: {$randomGrn}]: {$note}";
            }

            $adjDate = Carbon::now()->subDays(rand(0, 25))->subHours(rand(1, 23));

            StockTransaction::create([
                'stock_id' => $stk->stock_id,
                'product_id' => $stk->product_id,
                'branch_id' => $stk->branch_id,
                'brand_id' => $stk->brand_id,
                'category_id' => $stk->category_id,
                'subcategory_id' => $stk->subcategory_id,
                'product_name' => $stk->product_name,
                'transaction_type' => 'adjustment',
                'quantity_before' => $quantityBefore,
                'quantity_changed' => $discrepancy,
                'quantity_after' => $quantityAfter,
                'notes' => $note,
                'created_by' => $admin->id,
                'created_at' => $adjDate,
                'updated_at' => $adjDate
            ]);
        }

        // 20. Explicitly seed additional movement history for testing MacBook Air M3 (Product ID 5, Branch ID 1)
        $macbookStock = \App\Models\Stock::where('product_id', 5)->where('branch_id', 1)->first();
        if ($macbookStock) {
            // Re-establish stock at initial level 105
            $macbookStock->update(['quantity' => 105]);
            
            // Delete any existing transactions to avoid duplicates
            StockTransaction::where('product_id', 5)->where('branch_id', 1)->delete();
            
            // Initial
            StockTransaction::create([
                'stock_id' => $macbookStock->stock_id,
                'product_id' => 5,
                'branch_id' => 1,
                'brand_id' => $macbookStock->brand_id,
                'category_id' => $macbookStock->category_id,
                'subcategory_id' => $macbookStock->subcategory_id,
                'product_name' => $macbookStock->product_name,
                'transaction_type' => 'initial',
                'quantity_before' => 0,
                'quantity_changed' => 105,
                'quantity_after' => 105,
                'reference_number' => 'INIT-STK-' . $macbookStock->stock_id,
                'notes' => 'Initial stock setup via seeder',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6)
            ]);

            // Sale (Invoice)
            StockTransaction::create([
                'stock_id' => $macbookStock->stock_id,
                'product_id' => 5,
                'branch_id' => 1,
                'brand_id' => $macbookStock->brand_id,
                'category_id' => $macbookStock->category_id,
                'subcategory_id' => $macbookStock->subcategory_id,
                'product_name' => $macbookStock->product_name,
                'transaction_type' => 'out',
                'quantity_before' => 105,
                'quantity_changed' => -5,
                'quantity_after' => 100,
                'reference_number' => 'INV-2026-0001',
                'notes' => 'Sold via invoice INV-2026-0001',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4)
            ]);

            // Stock Adjustment
            StockTransaction::create([
                'stock_id' => $macbookStock->stock_id,
                'product_id' => 5,
                'branch_id' => 1,
                'brand_id' => $macbookStock->brand_id,
                'category_id' => $macbookStock->category_id,
                'subcategory_id' => $macbookStock->subcategory_id,
                'product_name' => $macbookStock->product_name,
                'transaction_type' => 'adjustment',
                'quantity_before' => 100,
                'quantity_changed' => -2,
                'quantity_after' => 98,
                'reference_number' => 'ADJ-2026-0042',
                'notes' => 'Damaged inventory written off',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ]);

            // Return
            StockTransaction::create([
                'stock_id' => $macbookStock->stock_id,
                'product_id' => 5,
                'branch_id' => 1,
                'brand_id' => $macbookStock->brand_id,
                'category_id' => $macbookStock->category_id,
                'subcategory_id' => $macbookStock->subcategory_id,
                'product_name' => $macbookStock->product_name,
                'transaction_type' => 'in',
                'quantity_before' => 98,
                'quantity_changed' => 1,
                'quantity_after' => 99,
                'reference_number' => 'RET-2026-0003',
                'notes' => 'Returned from invoice INV-2026-0001 (Sellable)',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ]);

            // Stock Transfer Out
            StockTransaction::create([
                'stock_id' => $macbookStock->stock_id,
                'product_id' => 5,
                'branch_id' => 1,
                'brand_id' => $macbookStock->brand_id,
                'category_id' => $macbookStock->category_id,
                'subcategory_id' => $macbookStock->subcategory_id,
                'product_name' => $macbookStock->product_name,
                'transaction_type' => 'out',
                'quantity_before' => 99,
                'quantity_changed' => -10,
                'quantity_after' => 89,
                'reference_number' => 'TRF-2026-0012',
                'notes' => 'Stock Transfer to Branch [Kandy Branch]',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ]);

            // Purchase (GRN)
            StockTransaction::create([
                'stock_id' => $macbookStock->stock_id,
                'product_id' => 5,
                'branch_id' => 1,
                'brand_id' => $macbookStock->brand_id,
                'category_id' => $macbookStock->category_id,
                'subcategory_id' => $macbookStock->subcategory_id,
                'product_name' => $macbookStock->product_name,
                'transaction_type' => 'in',
                'quantity_before' => 89,
                'quantity_changed' => 20,
                'quantity_after' => 109,
                'reference_number' => 'GRN-2026-0095',
                'notes' => 'Purchase order stock received via GRN-2026-0095',
                'created_by' => $admin->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $macbookStock->update(['quantity' => 109]);
        }

        // 21. Explicitly seed additional movement history for testing iPhone 15 Pro (Product ID 1, Branch ID 1)
        $iphoneStock = \App\Models\Stock::where('product_id', 1)->where('branch_id', 1)->first();
        if ($iphoneStock) {
            // Re-establish stock at initial level 44
            $iphoneStock->update(['quantity' => 44]);
            
            // Delete any existing transactions to avoid duplicates
            StockTransaction::where('product_id', 1)->where('branch_id', 1)->delete();
            
            // Initial
            StockTransaction::create([
                'stock_id' => $iphoneStock->stock_id,
                'product_id' => 1,
                'branch_id' => 1,
                'brand_id' => $iphoneStock->brand_id,
                'category_id' => $iphoneStock->category_id,
                'subcategory_id' => $iphoneStock->subcategory_id,
                'product_name' => $iphoneStock->product_name,
                'transaction_type' => 'initial',
                'quantity_before' => 0,
                'quantity_changed' => 44,
                'quantity_after' => 44,
                'reference_number' => 'INIT-STK-' . $iphoneStock->stock_id,
                'notes' => 'Initial stock setup via seeder',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6)
            ]);

            // Sale (Invoice)
            StockTransaction::create([
                'stock_id' => $iphoneStock->stock_id,
                'product_id' => 1,
                'branch_id' => 1,
                'brand_id' => $iphoneStock->brand_id,
                'category_id' => $iphoneStock->category_id,
                'subcategory_id' => $iphoneStock->subcategory_id,
                'product_name' => $iphoneStock->product_name,
                'transaction_type' => 'out',
                'quantity_before' => 44,
                'quantity_changed' => -2,
                'quantity_after' => 42,
                'reference_number' => 'INV-2026-0002',
                'notes' => 'Sold via invoice INV-2026-0002',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4)
            ]);

            // Clean existing price histories to avoid duplicates
            \App\Models\ProductPriceHistory::where('product_id', 1)->delete();
            
            // Initial price history record
            \App\Models\ProductPriceHistory::create([
                'product_id' => 1,
                'old_cost_price' => 0.00,
                'new_cost_price' => 7000.00,
                'old_selling_price' => 0.00,
                'new_selling_price' => 10000.00,
                'user_id' => $admin->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5)
            ]);

            // Price change history record (this will show in red on Bin Card since old_selling > 0)
            \App\Models\ProductPriceHistory::create([
                'product_id' => 1,
                'old_cost_price' => 7000.00,
                'new_cost_price' => 7200.00,
                'old_selling_price' => 10000.00,
                'new_selling_price' => 11500.00,
                'user_id' => $admin->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ]);

            // Stock Adjustment
            StockTransaction::create([
                'stock_id' => $iphoneStock->stock_id,
                'product_id' => 1,
                'branch_id' => 1,
                'brand_id' => $iphoneStock->brand_id,
                'category_id' => $iphoneStock->category_id,
                'subcategory_id' => $iphoneStock->subcategory_id,
                'product_name' => $iphoneStock->product_name,
                'transaction_type' => 'adjustment',
                'quantity_before' => 42,
                'quantity_changed' => -1,
                'quantity_after' => 41,
                'reference_number' => 'ADJ-2026-0043',
                'notes' => 'Damaged inventory written off',
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ]);

            // Purchase (GRN)
            StockTransaction::create([
                'stock_id' => $iphoneStock->stock_id,
                'product_id' => 1,
                'branch_id' => 1,
                'brand_id' => $iphoneStock->brand_id,
                'category_id' => $iphoneStock->category_id,
                'subcategory_id' => $iphoneStock->subcategory_id,
                'product_name' => $iphoneStock->product_name,
                'transaction_type' => 'in',
                'quantity_before' => 41,
                'quantity_changed' => 10,
                'quantity_after' => 51,
                'reference_number' => 'GRN-2026-0096',
                'notes' => 'Purchase order stock received via GRN-2026-0096',
                'created_by' => $admin->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $iphoneStock->update(['quantity' => 51]);
        }
    }

    private function getRealisticProductName($brandName, $subName, $index)
    {
        switch ($subName) {
            case 'Smartphones':
                $models = ['Galaxy S', 'Galaxy A', 'Nord', 'Redmi Note', 'Edge', 'Xperia'];
                $model = $models[abs(crc32($brandName)) % count($models)];
                if (strcasecmp($brandName, 'Apple') === 0) {
                    return "iPhone " . (11 + ($index % 6)) . ($index % 2 === 0 ? " Pro" : " Max");
                }
                return $brandName . " " . $model . " " . ($index + 10);
                
            case 'Laptops':
                $models = ['ThinkPad X1', 'Spectre x360', 'Inspiron', 'Yoga Slim', 'ZenBook', 'Predator', 'Blade'];
                $model = $models[abs(crc32($brandName)) % count($models)];
                if (strcasecmp($brandName, 'Apple') === 0) {
                    return "MacBook Pro " . (13 + ($index % 3)) . "-inch M" . (($index % 3) + 1);
                }
                return $brandName . " " . $model . " " . ($index + 1);
                
            case 'Audio':
                $models = ['Wireless Headphones', 'True Wireless Earbuds', 'Noise Cancelling Buds', 'Home Speaker', 'Bluetooth Soundbar'];
                $model = $models[abs(crc32($brandName)) % count($models)];
                return $brandName . " " . $model . " " . (100 + $index * 5);
                
            case 'Accessories':
                $models = ['USB-C Docking Station', 'Wireless Mouse', 'Mechanical Keyboard', 'Power Bank 20K', 'High-Speed HDMI Cable'];
                $model = $models[abs(crc32($brandName)) % count($models)];
                return $brandName . " " . $model . " " . (200 + $index);
                
            case 'Smart Home':
                $models = ['Smart Plug WiFi', 'Security Camera 4K', 'Smart Thermostat', 'Motion Sensor', 'Smart LED Bulb Pack'];
                $model = $models[abs(crc32($brandName)) % count($models)];
                return $brandName . " " . $model;
                
            case 'T-Shirts':
                $styles = ['Dri-FIT Athletic', 'Original Classic Fit', 'Soft Cotton V-Neck', 'Crewneck Performance', 'Graphic Print'];
                $style = $styles[abs(crc32($brandName)) % count($styles)];
                return $brandName . " " . $style . " T-Shirt";
                
            case 'Jeans':
                $cuts = ['501 Straight Fit', 'Slim Fit Denim', 'Relaxed Fit Jeans', 'Bootcut Indigo', 'Skinny Stretch Jeans'];
                $cut = $cuts[abs(crc32($brandName)) % count($cuts)];
                return $brandName . " " . $cut;
                
            case 'Jackets':
                $types = ['Rainproof Windbreaker', 'Fleece Winter Jacket', 'Waterproof Parka', 'Casual Denim Jacket', 'Active Sports Hoodie'];
                $type = $types[abs(crc32($brandName)) % count($types)];
                return $brandName . " " . $type;
                
            case 'Socks':
                $types = ['Cushioned Crew Socks', 'Ankle Running Socks', 'No-Show Sports Liner', 'Merino Wool Trail Socks', 'Compression Athletic Socks'];
                $type = $types[abs(crc32($brandName)) % count($types)];
                return $brandName . " " . $type;
                
            case 'Caps':
                $types = ['Caps Hat', 'Snapback Trucker Hat', 'Sport Adjustable Visor', 'Knit Warm Beanie', 'Running Light Cap'];
                $type = $types[abs(crc32($brandName)) % count($types)];
                return $brandName . " " . $type;
                
            case 'Coffee Makers':
                return $brandName . " Espresso Machine " . ($index + 1);
                
            case 'Microwaves':
                return $brandName . " Countertop Microwave " . (500 + $index * 10);
                
            case 'Blenders':
                return $brandName . " High-Speed Blender " . ($index + 1);
                
            case 'Toasters':
                return $brandName . " " . (($index % 2 === 0) ? '2-Slice' : '4-Slice') . " Toaster";
                
            case 'Air Fryers':
                return $brandName . " Digital Air Fryer " . ($index + 3) . "L";
                
            case 'Footballs':
                return $brandName . " Match Football " . ($index + 1);
                
            case 'Dumbbells':
                return $brandName . " Hex Dumbbell " . (($index % 5 + 1) * 5) . "kg";
                
            case 'Yoga Mats':
                return $brandName . " Premium Yoga Mat " . (($index % 2 === 0) ? '5mm' : '6mm');
                
            case 'Water Bottles':
                return $brandName . " Insulated Bottle " . (($index % 2 === 0) ? '24oz' : '32oz');
                
            case 'Running Shoes':
                return $brandName . " Pro Run " . ($index + 1) . " Shoes";
                
            case 'Notebooks':
                return $brandName . " A5 Hardcover Notebook";
                
            case 'Pens':
                return $brandName . " Gel Pens " . (($index % 2 === 0) ? '3-Pack' : '6-Pack');
                
            case 'Desk Organizers':
                return $brandName . " Office Desk Organizer";
                
            case 'Staplers':
                return $brandName . " Heavy Duty Stapler";
                
            case 'Paper Shredders':
                return $brandName . " Cross-Cut Shredder";
                
            default:
                return $brandName . " Premium " . $subName . " Item " . $index;
        }
    }
}
