<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {   //марка, модель, год, пробег, цена, фото;
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');           // Manufacturer
            $table->string('model');           // Model + Badge + BadgeDetail
            $table->integer('year')->nullable(); 
            $table->integer('mileage')->default(0); 
            $table->bigInteger('price')->default(0); // Price (в вонах)
            $table->string('image_url')->nullable(); // первое фото из Photos
            $table->string('external_id')->unique(); 
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
