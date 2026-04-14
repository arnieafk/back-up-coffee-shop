<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table): void {
            $table->unsignedBigInteger('adminID')->nullable()->after('staffID');
            $table->foreign('adminID')->references('adminID')->on('admins')->nullOnDelete();
        });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->unsignedBigInteger('adminID')->nullable()->after('itemID');
            $table->foreign('adminID')->references('adminID')->on('admins')->nullOnDelete();
        });

        Schema::table('inventories', function (Blueprint $table): void {
            $table->unsignedBigInteger('adminID')->nullable()->after('inventoryID');
            $table->foreign('adminID')->references('adminID')->on('admins')->nullOnDelete();
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('adminID')->nullable()->after('orderID');
            $table->foreign('adminID')->references('adminID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['adminID']);
            $table->dropColumn('adminID');
        });

        Schema::table('inventories', function (Blueprint $table): void {
            $table->dropForeign(['adminID']);
            $table->dropColumn('adminID');
        });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropForeign(['adminID']);
            $table->dropColumn('adminID');
        });

        Schema::table('staff', function (Blueprint $table): void {
            $table->dropForeign(['adminID']);
            $table->dropColumn('adminID');
        });
    }
};

