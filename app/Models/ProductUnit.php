<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;
    
    protected $table = 'ProductUnits';
    protected $fillable = ['unit_id', 'unit_name'];
    public $timestamps = false;
}
