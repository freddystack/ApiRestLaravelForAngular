<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures_models', function (Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->decimal('price')->default(0);
            $table->integer('units')->default(0);
            $table->integer('subtotal')->default(0);
            $table->decimal('iva')->default(0)->nullable();
            $table->decimal('total')->default(0);
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
        Schema::dropIfExists('factures_models');
    }
}
