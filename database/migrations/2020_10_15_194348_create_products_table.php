<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->text('descripcion');
            $table->char('disponible',2);
            $table->string('imagen')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('precio-actual',5,2)->default(0);
            $table->decimal('precio-anterior',5,2)->default(0)->nullable();
            $table->integer('descuento')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
