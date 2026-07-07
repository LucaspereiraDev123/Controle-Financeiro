<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Necessária para a verificação de e-mail (Fortify). A tabela usuarios
     * foi criada sem esta coluna, embora o model já a referencie no cast.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
};
