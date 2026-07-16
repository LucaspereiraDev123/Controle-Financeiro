<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Momento em que o cliente cancelou a assinatura. O acesso continua até o
     * fim do período já pago (assinatura_ativa_ate), então esta coluna é o que
     * distingue "renova automaticamente" de "não renova mais".
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('assinatura_cancelada_em')->nullable()->after('mp_preapproval_id');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('assinatura_cancelada_em');
        });
    }
};
