<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // Item name
            $table->string('unit'); // e.g. ml, grams, pcs
            $table->integer('quantity')->default(0); // current stock
            $table->integer('reorder_level')->default(10); // alert level

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
