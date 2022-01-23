<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user_id', 'shift_id', 'created_at', 'status', 'total_revenue', 'total_profit', 'change', 'received', 'num_products'];
}
