<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->foreignId('userID')->constrained('users')->onDelete('cascade');
            $table->dateTime('orderDate');
            $table->string('status')->default('Pending');
            $table->decimal('totalAmount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
