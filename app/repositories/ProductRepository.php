<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\DB;

class ProductRepository 
{
    public static function find($opt, &$total = null)
    {
        // The query options
        $options = [
            'search' => null,
            'searchBy' => ['products.name', 'products.description'],
            'categoryId' => null,
            'paged' => 1,           // Current page - for pagination calculation
            'pageSize' => 30,      // Nums of products per page - for pagination calculation
            'orderby' => 'category',
            'orderbyOptions' => [         // The possible value for 'orderby'
                'category' => 'category_names, products.id', 
                'price_asc' => 'products.price ASC, products.id',
                'price_desc' => 'products.price DESC, products.id',
            ], 
        ];
        // Assign request params to options
        $options = collect($options)->replace( collect($opt)->only(array_keys($options)));

        /* Build the Query */

        // Subquery to get product's categories name & id
        $subQuery = 
            DB::table('ProductCategories')
            ->selectRaw('
                product_id, 
                GROUP_CONCAT(categories.name) as category_names, 
                GROUP_CONCAT(categories.id) as categoryIds
            ')
            ->join('Categories', 'ProductCategories.category_id', '=', 'Categories.id')
            ->groupBy('product_id');

        // The query
        $query = 
            DB::table('Products')
            ->selectRaw('
                products.*,
                T1.*,
                ProductUnits.unit_name
            ')
            ->join('ProductUnits', 'Products.unit_id', '=', 'ProductUnits.unit_id' )
            ->joinSub($subQuery, 'T1', 'Products.id', '=', 'T1.product_id');
        if ($options['search'])
        {
            foreach ($options['searchBy'] as $search_by)
                $query->whereRaw("{$search_by} LIKE '%{$options['search']}%'", [], 'or');
        }
        if ($options['categoryId'])
        {
            $query->whereRaw("LOCATE(',{$options['categoryId']},', CONCAT(',', categoryIds, ',')) > 0");
        }

        // Get result
        if (isset($total)) {
            $sql = $query->toSql();
            $countQuery = DB::select(DB::raw("SELECT COUNT(1) as total FROM ( {$sql} ) as countQuery "));
            $total = $countQuery[0]->total;
        }
        $query = $query
            ->orderByRaw($options['orderbyOptions'][$options['orderby']])
            ->skip(($options['paged'] - 1) * $options['pageSize'])
            ->take($options['pageSize']);
        return $query->get();
    }

    public static function create($model)
    {
        // Insert Product
        Product::create($model);
        $product_id = Product::max('id');
  
        // Insert Product Categories
        $categoryIds = is_string($model['category_ids']) 
                        ? explode(',', $model['category_ids'])
                        : $model['category_ids'];
        foreach ($categoryIds as $cat_id) 
        {
            ProductCategory::create(['product_id' => $product_id, 'category_id' => $cat_id ]);
        }

        return $product_id;
    }

    public static function update($model)
    {
        // Update Product
        $keys = ['name', 'cost', 'price', 'quantity', 'unit_id', 'thumb_path', 'description'];
        Product::where('id', '=', $model['id'])->update(collect($model)->only($keys)->toArray());
        
        // Update Product's Categories
        if (in_array('category_ids', array_keys($model)))
        {
            // Remove old product's categories
            ProductCategory::where('product_id', '=', $model['id'])->delete();

            $categoryIds = is_string($model['category_ids']) 
                            ? explode(',', $model['category_ids'])
                            : $model['category_ids'];
            foreach ($categoryIds as $cat_id) 
            {
                ProductCategory::create(['product_id' => $model['id'], 'category_id' => $cat_id ]);
            }
        }
    }

    public static function getById($id)
    {
        $subQuery = 
            DB::table('ProductCategories')
            ->select('
                product_id,
                GROUP_CONCAT(category_id) as category_ids,
                GROUP_CONCAT(name) as category_names
            ')
            ->join('Categories', 'category_id', '=', 'id');
        
        return 
            DB::table('Products')
            ->selectRaw('
                Product.*,
                T1.*
            ')
            ->joinSub($subQuery, 'T1', 'id', '=', 'product_id')
            ->whereRaw("id = {$id}")->first();
    }

    public static function deleteById($id)
    {
        return DB::table('Products')->whereRaw("id = {$id}")->delete() > 0;
    }

    public static function deleteByIds($ids)
    {
        $ids = implode(',', $ids);
        return DB::table('Products')->whereRaw("id IN ({$ids})")->delete() > 0;
    }
}