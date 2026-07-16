<?php

namespace App\Models;

use App\Notifications\RedefinirSenhaNotification;
use App\Notifications\VerificarEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'trial_ends_at',
        'assinatura_ativa_ate',
        'mp_preapproval_id',
        'termos_aceitos_em',
        'is_admin',
    ];

    /**
     * Envia o e-mail de verificação em PT-BR e enfileirado.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerificarEmailNotification());
    }

    /**
     * Envia o e-mail de redefinição de senha em PT-BR e enfileirado.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new RedefinirSenhaNotification($token));
    }

    /**
     * Transações que pertencem a este usuário.
     */
    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacao::class, 'usuario_id');
    }

    /**
     * Categorias que pertencem a este usuário.
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class, 'usuario_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'assinatura_ativa_ate' => 'datetime',
            'termos_aceitos_em' => 'datetime',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * O usuário tem acesso liberado se a assinatura paga está vigente ou se
     * ainda está dentro do período de teste.
     */
    public function assinaturaAtiva(): bool
    {
        if ($this->assinatura_ativa_ate && $this->assinatura_ativa_ate->isFuture()) {
            return true;
        }

        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Pode lançar/alterar dados? Sem assinatura vigente nem trial, o usuário
     * continua entrando e consultando o que já registrou, mas em modo leitura.
     *
     * Ponto único da decisão: o middleware de escrita e a diretiva @podeEditar
     * das views consultam este método.
     */
    public function podeEditar(): bool
    {
        return $this->assinaturaAtiva();
    }

    /**
     * Estado atual da assinatura: 'ativa' (paga), 'trial' (em teste) ou
     * 'expirada' (sem acesso).
     */
    public function statusAssinatura(): string
    {
        if ($this->assinatura_ativa_ate && $this->assinatura_ativa_ate->isFuture()) {
            return 'ativa';
        }

        if ($this->trial_ends_at && $this->trial_ends_at->isFuture()) {
            return 'trial';
        }

        return 'expirada';
    }

    /**
     * Dias restantes do período de teste (0 se já acabou).
     */
    public function diasRestantesTrial(): int
    {
        if (! $this->trial_ends_at || $this->trial_ends_at->isPast()) {
            return 0;
        }

        return (int) ceil(now()->floatDiffInDays($this->trial_ends_at));
    }
}
