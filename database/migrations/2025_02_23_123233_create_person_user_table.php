<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('person_user', function (Blueprint $table) {
            // Chave primária autoincremental
            $table->id();
            
            // Chave estrangeira referenciando a tabela 'credential'
            $table->integer('id_credential'); 

            // Chave estrangeira referenciando a tabela 'persons'
            $table->integer('id_person');

            // E-mail do usuário, deve ser único no sistema
            $table->string('email')->unique();
            
            // Senha do usuário (armazenada de forma hash)
            $table->string('password');

            // Armazena a data/hora em que o e-mail do usuário foi verificado
            // Caso seja NULL, significa que o usuário ainda não confirmou o e-mail
            $table->timestamp('email_verified_at')->nullable();

            // Token usado para verificar o e-mail do usuário
            // Enviado por e-mail para confirmar a conta após o cadastro
            $table->string('verification_token', 255)->nullable();

            // Token usado para recuperação de senha
            // Criado quando o usuário solicita a redefinição de senha e expira após um tempo determinado
            $table->string('password_reset_token', 255)->nullable();

            // Registra a última vez que o usuário fez login no sistema
            $table->timestamp('last_login_at')->nullable();

            // Define se o usuário está ativo (1 = ativo, 0 = inativo)
            $table->integer('active')->default(1);

            // Cria os campos 'created_at' (data/hora de criação) e 'updated_at' (data/hora da última atualização do registro)
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_user');
    }
};
