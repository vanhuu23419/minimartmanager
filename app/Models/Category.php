<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'Categories';
    protected $fillable = ['name'];
    public $timestamps = false;

    public static function default() 
    {
        $model = new Category();
        $model->name = '';

        return $model; 
    }
}
