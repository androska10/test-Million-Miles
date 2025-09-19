<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            
            $table->renameColumn('manufacturer', 'brand'); 
            $table->renameColumn('photo_url', 'image_url');

            
            $table->integer('year')->nullable()->change();
            $table->integer('mileage')->default(0)->change();
            $table->bigInteger('price')->default(0)->change();
            $table->string('image_url')->nullable()->change();

           
            $table->string('external_id')->unique()->after('id');
            $table->json('data')->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            
            $table->dropColumn('external_id');
            $table->dropColumn('data');

           
            $table->renameColumn('brand', 'manufacturer');
            $table->renameColumn('image_url', 'photo_url');

           
            $table->unsignedSmallInteger('year')->change();
            $table->unsignedInteger('mileage')->change();
            $table->unsignedBigInteger('price')->change();
            $table->string('photo_url')->change(); 
        });
    }
};
