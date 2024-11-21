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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Nome do paciente
            $table->date('birthdate');             // Data de nascimento
            $table->string('cpf')->unique();       // CPF do paciente
            $table->string('email')->nullable();   // Email do paciente
            $table->string('phone')->nullable();   // Telefone do paciente
            $table->string('address')->nullable(); // Endereço do paciente
            $table->text('allergies')->nullable(); // Alergias do paciente
            $table->text('medical_history')->nullable(); // Histórico médico
            $table->string('marital_status')->nullable(); // Estado civil
            $table->string('nationality')->nullable();   // Nacionalidade
            $table->string('birthplace')->nullable();    // Naturalidade
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
