<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('brand')->nullable();
            $table->string('product_quantity')->nullable();
            $table->string('warranty')->nullable();
            $table->string('warranty_duration')->nullable();
            $table->integer('used')->nullable();
            $table->string('return_reason')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
};
