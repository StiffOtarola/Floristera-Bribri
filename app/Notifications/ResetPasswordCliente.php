<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordCliente extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expira = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);
        $tienda = config('floristeria.nombre', 'Floristería Bribrí');

        return (new MailMessage)
            ->subject('Restablecé tu contraseña · ' . $tienda)
            ->greeting('Hola ' . ($notifiable->nombre ?? '') . ' 🌸')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer mi contraseña', $url)
            ->line('Este enlace vence en ' . $expira . ' minutos.')
            ->line('Si no fuiste vos, podés ignorar este correo con tranquilidad: tu contraseña seguirá igual.')
            ->salutation('Con cariño, ' . $tienda);
    }
}
