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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome ou razão social
            $table->string('fantasy_name')->nullable(); // Nome fantasia
            $table->string('cpf_cnpj'); // CPF ou CNPJ
            $table->enum('category', ['Fornecedor', 'Outros']); // Categoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
