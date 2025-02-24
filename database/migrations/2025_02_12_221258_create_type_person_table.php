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
        Schema::create('type_person', function (Blueprint $table) {
            $table->id();
            $table->integer('id_credential');
            $table->string('name')->unique();
            $table->integer('active')->default(1); // Define o campo `active` como inteiro e o valor padrão como 1
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_person');
    }
};
