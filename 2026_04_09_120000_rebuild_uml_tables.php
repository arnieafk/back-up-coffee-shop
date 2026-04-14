<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('admins');

        Schema::create('admins', function (Blueprint $table) {
            $table->id('adminID');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->id('staffID');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('role');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id('customerID');
            $table->string('name');
            $table->string('phone');
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id('itemID');
            $table->string('itemName');
            $table->string('category');
            $table->decimal('price', 10, 2);
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id('inventoryID');
            $table->unsignedBigInteger('itemID');
            $table->integer('stockQuantity')->default(0);
            $table->integer('reorderLevel')->default(0);
            $table->dateTime('lastUpdated')->nullable();

            $table->foreign('itemID')->references('itemID')->on('menu_items')->cascadeOnDelete();
            $table->unique('itemID');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->unsignedBigInteger('customerID');
            $table->unsignedBigInteger('staffID')->nullable();
            $table->dateTime('orderDate');
            $table->string('status')->default('Pending');
            $table->decimal('totalAmount', 10, 2)->default(0);

            $table->foreign('customerID')->references('customerID')->on('customers')->cascadeOnDelete();
            $table->foreign('staffID')->references('staffID')->on('staff')->nullOnDelete();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('orderItemID');
            $table->unsignedBigInteger('orderID');
            $table->unsignedBigInteger('itemID');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);

            $table->foreign('orderID')->references('orderID')->on('orders')->cascadeOnDelete();
            $table->foreign('itemID')->references('itemID')->on('menu_items')->restrictOnDelete();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id('paymentID');
            $table->unsignedBigInteger('orderID');
            $table->string('paymentType');
            $table->decimal('amount', 10, 2);
            $table->dateTime('paymentDate')->nullable();

            $table->foreign('orderID')->references('orderID')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('admins');
    }
};

