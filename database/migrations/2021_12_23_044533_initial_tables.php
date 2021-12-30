<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ProductUnits', function(Blueprint $table) {
            $table->string('unit_id', 20)->primary();
            $table->string('unit_name', 50);
        });

        Schema::create('Products', function(Blueprint $table) {

            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->decimal('cost', 8, 2, true)->nullable();
            $table->decimal('price', 8, 2, true)->nullable();
            $table->integer('quantity', false, true)->default(0);
            $table->string('unit_id')->nullable();
            $table->string('thumb_path')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ProductUnits');
        Schema::dropIfExists('Products');
    }
}
