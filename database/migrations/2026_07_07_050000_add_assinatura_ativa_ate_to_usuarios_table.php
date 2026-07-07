<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data até a qual a assinatura paga está válida. Fica NULL enquanto o
     * usuário só tem o período de teste (trial_ends_at). Quando o gateway de
     * pagamento for integrado (Fase 3/4), ele passa a preencher esta coluna
     * a cada ciclo pago.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('assinatura_ativa_ate')->nullable()->after('trial_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('assinatura_ativa_ate');
        });
    }
};
