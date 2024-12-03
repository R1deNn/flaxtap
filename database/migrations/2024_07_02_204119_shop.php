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
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->integer('id_category');
            $table->integer('id_vobler')->nullable();
            $table->string('title');
            $table->text('description');
            $table->integer('default_price');
            $table->integer('price_student');
            $table->integer('price_opt_student');
            $table->integer('amount');
            $table->integer('amount_buys')->default(0);
            $table->integer('active')->default(1);
            $table->text('image')->nullable();
            $table->json('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
