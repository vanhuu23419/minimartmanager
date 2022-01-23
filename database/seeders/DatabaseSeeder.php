<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Receipt;
use Illuminate\Support\Str;
use App\Models\ReceiptProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    protected function clearData() 
    {
        DB::table('Categories')->delete();
        DB::table('ProductUnits')->delete();
        DB::table('Products')->delete();
        DB::table('ProductCategories')->delete();
        DB::table('Receipts')->delete();
        DB::table('ReceiptProducts')->delete();
        DB::table('POSShifts')->delete();
    }

    protected function seedProductUnits() 
    {
        $units = [
            [ 'unit_id' => 'peice', 'unit_name' => 'cái'], 
            [ 'unit_id' => 'bottle', 'unit_name' => 'chai'], 
            [ 'unit_id' => 'can', 'unit_name' => 'lon'],
            [ 'unit_id' => 'bag', 'unit_name' => 'bì'], 
            [ 'unit_id' => 'box', 'unit_name' => 'thùng'],
        ];

        DB::table('ProductUnits')->insert($units);
    }

    protected function seedCategories() 
    {
        $cats = [
            ['name' => 'Đồ uống'], 
            ['name' => 'Thực phẩm'], 
            ['name' => 'Bánh kẹo'],
        ];

        DB::table('Categories')->insert($cats);
    }

    protected function seedProducts() 
    {
        $units = DB::table('ProductUnits')->get();
        $categories = DB::table('Categories')->get();

        $numProducts = 150;
        for ($i=0; $i < $numProducts; $i++) 
        { 
            $cost = (rand(2000, 50000) * 4) / 3;
            $price = $cost * 1.25; 
            DB::table('Products')->insert([
                'name' => Str::random(rand(20, 50)),
                'cost' => $cost,
                'price' => $price,
                'quantity' => 10000,
                'unit_id' => $units[rand(0, count($units) - 1)]->unit_id,
                'thumb_path' => 'product_img/default.jpg',
                'description' => Str::random(100),
            ]);

            $product_id = DB::table('Products')->max('id');

            $numCats = rand(0,count($categories) - 1);
            for ($j=0; $j <= $numCats; $j++) { 
                $cat = $categories[$j];
                DB::table('ProductCategories')->insert([
                    'product_id' => $product_id,
                    'category_id' => $cat->id
                ]);
            }
        }
    }

    protected function seedReceipts()
    {
        $maxProductId = Product::max('id');
        $months = [6,12];
        $days = 30;
        $years = [2022, 2021];

        for($y = 0; $y <= 1; ++$y)
        for ($i=1; $i <= $months[$y]; $i++) 
        { 
            for ($j=1; $j <= $days; $j++) 
            { 
                $totalSale = rand(70, 100);
                while($totalSale--)
                {
                    /* Seed Receipt Items */
                    $totalRevenue = 0;
                    $totalProfit = 0;
                    $numProducts = 0;
                    $receiptId = Receipt::max('id') + 1;
                    
                    $totalProducts = rand(1, 3);
                    while($totalProducts--)
                    {
                        $product = Product::where('id', '=', rand(1, $maxProductId))->first();
                        $quantity = rand(1, 3);
                        ReceiptProduct::create([
                            'product_id' => $product->id,
                            'receipt_id' => $receiptId,
                            'product_name' => $product->name,
                            'product_cost' => $product->cost,
                            'product_price' => $product->price,
                            'quantity' => $quantity,
                        ]);

                        $numProducts += $quantity;
                        $totalRevenue += $quantity * $product->price;
                        $totalProfit += $quantity * ($product->price - $product->cost);
                    }

                    /* Seed Receipt */
                    Receipt::create([
                        'total_revenue' => $totalRevenue,
                        'total_profit' => $totalProfit,
                        'received' => $totalRevenue,
                        'change' => 0,
                        'num_products' => $numProducts,
                        'created_at' => date('Y-m-d H:i:s', strtotime("{$years[$y]}-{$i}-{$j}"))
                    ]);

                }
            }
        }
    }

    public function run()
    {
        $this->clearData();
        $this->seedCategories();
        $this->seedProductUnits();
        $this->seedProducts();
        $this->seedReceipts();

    }
}
