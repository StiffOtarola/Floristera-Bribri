<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConfigurarTienda extends Command
{
    protected $signature   = 'tienda:configurar';
    protected $description = 'Configura el sistema para un nuevo negocio (nombre, colores, WhatsApp, etc.)';

    private array $paletas = [
        '🌸 Floristería / plantas (verde)'     => ['2A4A1E', '4A7A35', 'C4714A', 'E8B4A0', 'F8F5EE'],
        '🍞 Panadería / repostería (café)'      => ['5C3D1E', '8B6245', 'D4A853', 'F5E6D3', 'FDF8F0'],
        '👗 Boutique / ropa (rosa)'             => ['8B2252', 'C4457A', 'F0A0C0', 'FAD4E5', 'FFF5F8'],
        '🐾 Mascotas / veterinaria (azul)'      => ['1A3A5C', '2E6DA4', 'F5A623', 'D4E8FA', 'F0F7FF'],
        '🧴 Cosméticos / belleza (morado)'      => ['4A1A6B', '7B35A8', 'E8A0D4', 'F5D4EF', 'FDF5FF'],
        '🍕 Restaurante / comida (rojo)'        => ['7A1A1A', 'B03030', 'F5A623', 'FAD4C0', 'FFF8F5'],
        '🔧 Ferretería / servicios (gris azul)' => ['1A2E4A', '2E4A6B', 'E87820', 'D4E0F0', 'F0F4F8'],
        '💍 Joyería / accesorios (dorado)'      => ['2A1A00', '5C3D00', 'C4A020', 'F5E8C0', 'FFFDF0'],
        '✏️  Personalizar colores manualmente'  => [],
    ];

    private array $emojis = [
        'Floristería / plantas'   => '🌸',
        'Panadería / repostería'  => '🍞',
        'Boutique / ropa'         => '👗',
        'Mascotas / veterinaria'  => '🐾',
        'Cosméticos / belleza'    => '🧴',
        'Restaurante / comida'    => '🍕',
        'Ferretería / servicios'  => '🔧',
        'Joyería / accesorios'    => '💍',
        'Otro (escribir emoji)'   => '🛒',
    ];

    public function handle(): int
    {
        $this->newLine();
        $this->line('════════════════════════════════════════════════════');
        $this->line('█    🛒  CONFIGURADOR DE TIENDA EN LÍNEA           █');
        $this->line('█    Desarrollado por Berny Otárola                █');
        $this->line('════════════════════════════════════════════════════');
        $this->newLine();

        // ── 1. Nombre del negocio ─────────────────────────
        $nombre = $this->ask('📦 Nombre del negocio');
        if (empty(trim($nombre))) {
            $this->error('El nombre es obligatorio.');
            return self::FAILURE;
        }

        // ── 2. Slogan ─────────────────────────────────────
        $slogan = $this->ask('✨ Slogan del negocio (Enter para omitir)');

        // ── 3. Descripción SEO ────────────────────────────
        $desc = $this->ask('📝 Descripción breve para SEO (Enter para omitir)');

        // ── 4. Email admin ────────────────────────────────
        $email = $this->ask('📧 Email del administrador');

        // ── 5. WhatsApp ───────────────────────────────────
        $this->line('');
        $this->line('📱 <comment>Número de WhatsApp</comment> (formato internacional SIN + ni espacios)');
        $this->line('   Ejemplo Costa Rica: <info>50688001234</info>');
        $whatsapp = $this->ask('   WhatsApp');

        // ── 6. Dirección ──────────────────────────────────
        $direccion = $this->ask('📍 Dirección física del negocio');

        // ── 7. Horario ────────────────────────────────────
        $horario = $this->ask('🕐 Horario (ej: Lunes a Sábado: 8am - 6pm)');

        // ── 8. Costo de envío ─────────────────────────────
        $costoEnvio = $this->ask('🚚 Costo de envío a domicilio (en la moneda local, ej: 3000)', '3000');

        // ── 9. Prefijo de pedido ──────────────────────────
        $this->line('');
        $this->line('🔢 <comment>Prefijo de pedidos</comment> (3 letras, ej: BRI → pedido BRI-A1B2-2026)');
        $prefijo = strtoupper($this->ask('   Prefijo', 'TDA'));
        $prefijo = substr(preg_replace('/[^A-Z]/', '', $prefijo), 0, 3) ?: 'TDA';

        // ── 10. Emoji del negocio ─────────────────────────
        $this->newLine();
        $this->line('🎨 <comment>Selecciona el tipo de negocio para el emoji:</comment>');
        $tipoEmoji = $this->choice('Tipo de negocio', array_keys($this->emojis), 0);
        if ($tipoEmoji === 'Otro (escribir emoji)') {
            $emoji = $this->ask('   Escribe el emoji', '🛒');
        } else {
            $emoji = $this->emojis[$tipoEmoji];
        }

        // ── 11. Paleta de colores ─────────────────────────
        $this->newLine();
        $this->line('🎨 <comment>Selecciona la paleta de colores:</comment>');
        $paletaKey = $this->choice('Paleta', array_keys($this->paletas), 0);

        if ($paletaKey === '✏️  Personalizar colores manualmente') {
            $this->line('   Ingresá los colores en formato HEX <comment>SIN el #</comment> (ej: 2A4A1E)');
            $colorPrimario      = $this->ask('   Color primario (oscuro)',      '2A4A1E');
            $colorPrimarioClaro = $this->ask('   Color primario claro',         '4A7A35');
            $colorAcento        = $this->ask('   Color acento / botones',       'C4714A');
            $colorRosa          = $this->ask('   Color suave / hover',          'E8B4A0');
            $colorFondo         = $this->ask('   Color de fondo',               'F8F5EE');
        } else {
            [$colorPrimario, $colorPrimarioClaro, $colorAcento, $colorRosa, $colorFondo]
                = $this->paletas[$paletaKey];
        }

        // ── 12. Redes sociales ────────────────────────────
        $this->newLine();
        $this->line('📱 <comment>Redes sociales</comment> (Enter para omitir):');
        $facebook  = $this->ask('   Facebook URL');
        $instagram = $this->ask('   Instagram URL');
        $tiktok    = $this->ask('   TikTok URL');

        // ── 13. DB ────────────────────────────────────────
        $this->newLine();
        $this->line('🗄️  <comment>Base de datos MySQL:</comment>');
        $dbName = $this->ask('   Nombre de la base de datos', strtolower(preg_replace('/\s+/', '_', $nombre)));
        $dbUser = $this->ask('   Usuario', 'root');
        $dbPass = $this->secret('   Contraseña (Enter si no tiene)') ?? '';

        // ── Resumen ───────────────────────────────────────
        $this->newLine();
        $this->line('════════════════════════════════════════════════════');
        $this->line('█             RESUMEN DE CONFIGURACIÓN             █');
        $this->line('════════════════════════════════════════════════════');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Negocio',    $emoji . ' ' . $nombre],
                ['Slogan',     $slogan ?: '(sin slogan)'],
                ['Email',      $email],
                ['WhatsApp',   $whatsapp],
                ['Dirección',  $direccion ?: '(sin dirección)'],
                ['Horario',    $horario ?: '(sin horario)'],
                ['Costo envío','₡' . number_format((int)$costoEnvio)],
                ['Prefijo',    $prefijo],
                ['Paleta',     $paletaKey],
                ['Base datos', $dbName],
            ]
        );

        if (!$this->confirm('¿Todo correcto? ¿Guardamos esta configuración?', true)) {
            $this->warn('Configuración cancelada. Volvé a ejecutar el comando.');
            return self::FAILURE;
        }

        // ── Escribir .env ─────────────────────────────────
        $this->newLine();
        $this->line('✏️  Escribiendo configuración en <info>.env</info>...');

        $this->setEnv('APP_NAME',                    '"' . $nombre . '"');
        $this->setEnv('TIENDA_NOMBRE',               '"' . $nombre . '"');
        $this->setEnv('TIENDA_SLOGAN',               '"' . ($slogan ?: $nombre) . '"');
        $this->setEnv('TIENDA_DESC',                 '"' . ($desc ?: $nombre) . '"');
        $this->setEnv('TIENDA_ADMIN_EMAIL',          $email);
        $this->setEnv('TIENDA_EMOJI',                $emoji);
        $this->setEnv('TIENDA_WHATSAPP',             $whatsapp);
        $this->setEnv('TIENDA_DIRECCION',            '"' . ($direccion ?: '') . '"');
        $this->setEnv('TIENDA_HORARIO',              '"' . ($horario ?: '') . '"');
        $this->setEnv('TIENDA_COSTO_ENVIO',          $costoEnvio);
        $this->setEnv('TIENDA_PREFIJO_PEDIDO',       $prefijo);
        $this->setEnv('TIENDA_COLOR_PRIMARIO',       $colorPrimario);
        $this->setEnv('TIENDA_COLOR_PRIMARIO_CLARO', $colorPrimarioClaro);
        $this->setEnv('TIENDA_COLOR_ACENTO',         $colorAcento);
        $this->setEnv('TIENDA_COLOR_ROSA',           $colorRosa);
        $this->setEnv('TIENDA_COLOR_FONDO',          $colorFondo);
        $this->setEnv('TIENDA_FACEBOOK',             $facebook ?: '');
        $this->setEnv('TIENDA_INSTAGRAM',            $instagram ?: '');
        $this->setEnv('TIENDA_TIKTOK',               $tiktok ?: '');
        $this->setEnv('DB_DATABASE',                 $dbName);
        $this->setEnv('DB_USERNAME',                 $dbUser);
        $this->setEnv('DB_PASSWORD',                 $dbPass);

        // ── Pasos finales ─────────────────────────────────
        $this->newLine();
        $this->line('🔑 Generando APP_KEY...');
        $this->call('key:generate');

        $this->line('🗄️  Ejecutando migraciones...');
        $this->call('migrate', ['--force' => true]);

        $this->line('🌱 Ejecutando seeders...');
        $this->call('db:seed', ['--force' => true]);

        $this->line('🖼️  Creando link de storage...');
        $this->call('storage:link');

        $this->line('🧹 Limpiando caché...');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');

        // ── Resultado ─────────────────────────────────────
        $this->newLine();
        $this->line('════════════════════════════════════════════════════');
        $this->line('█        ✅  ¡TIENDA CONFIGURADA CON ÉXITO!        █');
        $this->line('════════════════════════════════════════════════════');
        $this->newLine();
        $this->info("  {$emoji} Negocio: {$nombre}");
        $this->info('  🌐 Abrí ' . env('APP_URL') . ' en el navegador');
        $this->info('  ⚙️  Panel admin: ' . env('APP_URL') . '/admin/dashboard');
        $this->newLine();
        $this->warn('  📋 Pasos pendientes:');
        $this->line('     1. Cambiar la contraseña del admin en el panel');
        $this->line('     2. Subir el logo en Configuración');
        $this->line('     3. Agregar productos y categorías');
        $this->line('     4. Configurar el correo SMTP en .env');
        $this->newLine();

        return self::SUCCESS;
    }

    private function setEnv(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), $envPath);
            } else {
                File::put($envPath, '');
            }
        }

        $content = File::get($envPath);

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $content
            );
        } else {
            $content .= "\n{$key}={$value}";
        }

        File::put($envPath, $content);
    }
}
