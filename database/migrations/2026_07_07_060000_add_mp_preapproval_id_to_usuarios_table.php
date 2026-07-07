<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Id da assinatura (preapproval) do Mercado Pago associada ao usuário.
     * Usado para correlacionar os webhooks de cobrança com a conta.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('mp_preapproval_id')->nullable()->after('assinatura_ativa_ate');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('mp_preapproval_id');
        });
    }
};
