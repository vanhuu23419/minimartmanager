<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'Products';
    protected $fillable = ['name', 'cost', 'price', 'quantity', 'unit_id', 'description', 'thumb_path'];
    public $timestamps = false;

    public function categories() 
    {
        return $this->belongsToMany(Category::class, 'ProductCategories', 'product_id', 'category_id');
    }

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id', 'unit_id');
    }

    public static function default()
    {
        $model = new Product();
        $model->name = '';
        $model->quantity = 0;
        $model->price = 0;
        $model->cost = 0;
        $model->description = '';
        $model->thumb_path = 'product_img/default.jpg';
        $model->unit_id = 'peice';

        return $model;
    }
}
