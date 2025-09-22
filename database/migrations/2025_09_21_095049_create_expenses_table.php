<?php
// database/migrations/2025_09_21_000001_create_expenses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('payment_method')->default('cash');
            $table->string('receipt_path')->nullable(); // for file uploads
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            $table->index(['user_id', 'date']);
            $table->index(['category_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};