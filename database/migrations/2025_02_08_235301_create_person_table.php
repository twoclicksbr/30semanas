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
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->integer('id_credential');
            $table->string('name');
            $table->integer('id_gender');
            $table->string('cpf')->unique();
            $table->date('dt_nascimento');
            $table->string('whatsapp')->unique();
            $table->string('email')->unique();
            $table->string('eklesia')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};
