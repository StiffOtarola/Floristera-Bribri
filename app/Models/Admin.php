<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable, CanResetPassword;

    protected $table = 'admins';

    protected $fillable = [
        'nombre',
        'email',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // ── Timestamps ───────────────────────────────────────
    // La tabla solo tiene creado_en, sin updated_at
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;

    // ── Auth: columna de contraseña ───────────────────────
    // La tabla usa password_hash en lugar del estándar "password"
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    // ── Auth: sin remember token ──────────────────────────
    // La tabla admins no tiene columna remember_token
    public function getRememberTokenName(): string
    {
        return '';
    }

    // ── Auth: notificación de reset de contraseña (en español) ──
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordCliente($token));
    }
}