<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Receipt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // POSShift
        Schema::create('POSShifts', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
        });
        // Receipt
        Schema::create('Receipts', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('shift_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('status', 10)->default('saved');
            $table->decimal('total', 8,2, true)->default(0);
            $table->decimal('change', 8,2, true)->default(0);
            $table->decimal('received', 8,2, true)->default(0);
            $table->smallInteger('num_products', false, true)->default(0);
        });
        // ReceiptProduct
        Schema::create('ReceiptProducts', function(Blueprint $table) {
            $table->integer('product_id')->nullable();
            $table->integer('receipt_id')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->decimal('product_cost', 8, 2, true)->default(0);
            $table->decimal('product_price', 8, 2, true)->default(0);
            $table->integer('quantity', false, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('POSShifts');
        Schema::dropIfExists('Receipts');
        Schema::dropIfExists('ReceiptProducts');
    }
}
