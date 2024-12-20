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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date'); // Aniversario
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('role'); // Role/Function
            $table->string('salary');
            $table->string('rg')->unique();
            $table->string('admission_date');
            $table->string('phone')->nullable();
            $table->enum('type', ['common', 'registered']); // commom or registered
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
