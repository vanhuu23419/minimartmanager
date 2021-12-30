<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
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
            $cost = (rand(2000, 500000) * 4) / 3;
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

    public function run()
    {
        $this->clearData();
        $this->seedCategories();
        $this->seedProductUnits();
        $this->seedProducts();
    }
}
