<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registra o momento em que o usuário aceitou os Termos de Uso e a
     * Política de Privacidade (consentimento LGPD). Fica após email_verified_at
     * para manter a ordem lógica das colunas de conta.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('termos_aceitos_em')->nullable()->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('termos_aceitos_em');
        });
    }
};
