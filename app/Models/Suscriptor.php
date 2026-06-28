<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Suscriptor extends Authenticatable
{
    protected $table = 'suscriptores';

    protected $fillable = [
        'nombre',
        'email',
        'password_hash',
        'activo',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // ── Timestamps ───────────────────────────────────────
    // La tabla solo tiene suscrito_en, sin updated_at
    const CREATED_AT = 'suscrito_en';
    const UPDATED_AT = null;

    // ── Auth: columna de contraseña ───────────────────────
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    // ── Auth: sin remember token ──────────────────────────
    // La tabla suscriptores no tiene columna remember_token
    public function getRememberTokenName(): string
    {
        return '';
    }

    // ── Relaciones ───────────────────────────────────────

    // Pedidos enlazados directamente a este cliente.
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Todos los pedidos del cliente: los enlazados por suscriptor_id
     * MÁS los hechos como invitado (sin enlazar) con el mismo email.
     * El OR va agrupado en un closure para que al encadenar otros where()
     * (ej. ->where('numero_pedido', ...)) no se rompa la precedencia AND/OR.
     */
    public function todosLosPedidos()
    {
        return Pedido::where(function ($q) {
                $q->where('suscriptor_id', $this->id)
                  ->orWhere(function ($q2) {
                      $q2->whereNull('suscriptor_id')
                         ->where('email_cliente', $this->email);
                  });
            })
            ->orderByDesc('creado_en');
    }

    // ── Accessors ────────────────────────────────────────

    // ¿Tiene cuenta con contraseña?
    public function getTieneCuentaAttribute(): bool
    {
        return !is_null($this->password_hash);
    }

    // Fecha de suscripción formateada
    public function getFechaFormateadaAttribute(): string
    {
        return $this->suscrito_en?->format('d/m/Y') ?? '—';
    }
}