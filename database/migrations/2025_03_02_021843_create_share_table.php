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
        Schema::create('participant', function (Blueprint $table) {
            $table->id();
            $table->integer('id_credential'); 

            $table->string('name')->unique();
            $table->integer('id_gender');
            $table->integer('id_person'); // Filtrar somente os registros de líder.
            $table->text('link'); // Link do google meet.

            // Define se o usuário está ativo (1 = ativo, 0 = inativo)
            $table->integer('active')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant');
    }
};
