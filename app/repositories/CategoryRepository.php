<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\DB;

class CategoryRepository 
{
    public static function find($opt, &$total = null)
    {
        // The query options
        $options = [
            'search' => null,
            'searchBy' => ['categories.name'],
            'paged' => 1,           // Current page - for pagination calculation
            'pageSize' => 30,      // Nums of products per page - for pagination calculation
        ];
        // Assign request params to options
        $options = collect($options)->replace( collect($opt)->only(array_keys($options)) );

        /* Build the Query */

        $query = 
            DB::table('Categories')
            ->selectRaw('
                Categories.id, Categories.name,
                COUNT( ProductCategories.product_id ) as num_products
            ')
            ->leftJoin('ProductCategories', 'Categories.id', '=', 'ProductCategories.category_id' )
            ->groupBy(['Categories.id', 'Categories.name']);

        if ($options['search'])
        {
            foreach ($options['searchBy'] as $search_by)
                $query->whereRaw("{$search_by} LIKE '%{$options['search']}%'", [], 'or');
        }

        // Get result
        if (isset($total)) {
            $sql = $query->toSql();
            $countQuery = DB::select(DB::raw("SELECT COUNT(1) as total FROM ( {$sql} ) as countQuery "));
            $total = $countQuery[0]->total;
        }
        $query = $query
            ->orderByRaw('categories.id')
            ->skip(($options['paged'] - 1) * $options['pageSize'])
            ->take($options['pageSize']);
        return $query->get();
    }

    public static function create($model)
    {
        Category::create($model);
        $categoryId = Category::max('id');
  
        return $categoryId;
    }

    public static function update($model)
    {
        $keys = ['name'];
        Category::where('id', '=', $model['id'])->update(collect($model)->only($keys)->toArray());
    }

    public static function deleteById($id)
    {
        return DB::table('Categories')->whereRaw("id = {$id}")->delete() > 0;
    }

    public static function deleteByIds($ids)
    {
        $ids = implode(',', $ids);
        return DB::table('Categories')->whereRaw("id IN ({$ids})")->delete() > 0;
    }
}