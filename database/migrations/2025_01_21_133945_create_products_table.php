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
            $table->string('article', 255)->unique();
            $table->string('name', 255);
            $table->enum('status', ['available', 'unavailable']);
            $table->json('data')->nullable(); // DATA: jsonb, может содержать Color, Size
            $table->timestamps(); // timestamps (created_at, updated_at)
            $table->softDeletes(); // soft deletes (deleted_at)
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
