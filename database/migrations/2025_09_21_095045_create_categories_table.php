<?php
// database/migrations/2025_09_21_000000_create_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null = global category
            $table->string('name');
            $table->string('color', 7)->default('#3B82F6'); // hex color for charts
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};