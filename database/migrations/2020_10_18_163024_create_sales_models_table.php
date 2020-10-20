<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_models', function (Blueprint $table) {
            $table->id();
            $table->decimal('profist')->default(0)->nullable();
            $table->decimal('losses')->default(0)->nullable();
            $table->string('best_seller')->nullable();
            $table->integer('quantity_best_seller')->nullable()->default(0)->unsigned();
            $table->string('less_sold')->nullable();
            $table->integer('quantity_less_sold')->nullable()->default(0)->unsigned();
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
        Schema::dropIfExists('sales_models');
    }
}
