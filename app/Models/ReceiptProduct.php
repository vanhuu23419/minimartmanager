<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptProduct extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'ReceiptProducts';
    protected $fillable = ['product_id', 'receipt_id', 'product_name', 'product_price', 'product_cost', 'quantity'];
}
