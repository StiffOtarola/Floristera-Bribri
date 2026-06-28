<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Cliente (suscriptor) dueño del pedido. Nullable: los pedidos
            // de invitados quedan sin enlazar y se reclaman por email_cliente.
            // unsignedInteger para calzar con suscriptores.id (increments = INT unsigned).
            $table->unsignedInteger('suscriptor_id')->nullable()->after('user_id');

            $table->foreign('suscriptor_id')
                  ->references('id')->on('suscriptores')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['suscriptor_id']);
            $table->dropColumn('suscriptor_id');
        });
    }
};
