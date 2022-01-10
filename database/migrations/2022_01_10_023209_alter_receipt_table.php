<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('Receipts');
        Schema::create('Receipts', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('shift_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('status', 10)->default('saved');
            $table->double('total', null, 2, true)->default(0);
            $table->double('received', null, 2, true)->default(0);
            $table->double('change', null, 2, true)->default(0);
            $table->smallInteger('num_products', false, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
