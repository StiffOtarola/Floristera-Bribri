@extends('layouts.main')
@section('title', 'Mi perfil')

@section('content')
@include('cliente.partials.header', ['active' => 'perfil'])

<div class="cuenta-wrap">

    @if($errors->any())
        <div class="c-alert c-alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- ── Datos personales ── --}}
    <div class="c-card">
        <h2 style="margin-bottom:1.25rem;">Mis datos</h2>
        <form method="POST" action="{{ route('cuenta.perfil.update') }}">
            @csrf
            @method('PUT')

            <div class="c-form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" maxlength="100" required>
            </div>

            <div class="c-form-group">
                <label>Correo electrónico</label>
                <input type="email" value="{{ $cliente->email }}" disabled>
                <p class="c-form-hint">El correo es tu identificador de acceso y no se puede cambiar aquí.</p>
            </div>

            <button type="submit" class="c-btn c-btn-primary">Guardar cambios</button>
        </form>
    </div>

    {{-- ── Cambiar contraseña ── --}}
    <div class="c-card">
        <h2 style="margin-bottom:1.25rem;">Cambiar contraseña</h2>
        <form method="POST" action="{{ route('cuenta.password.update') }}">
            @csrf
            @method('PUT')

            <div class="c-form-group">
                <label>Contraseña actual</label>
                <input type="password" name="password_actual" placeholder="••••••••" required>
            </div>
            <div class="c-form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
            </div>
            <div class="c-form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" placeholder="Repetí la nueva contraseña" required>
            </div>

            <button type="submit" class="c-btn c-btn-primary">Actualizar contraseña</button>
        </form>
    </div>

    {{-- ── Novedades / newsletter ── --}}
    <div class="c-card">
        <h2 style="margin-bottom:0.75rem;">Novedades por correo</h2>
        <p style="color:var(--gris);font-size:0.88rem;margin-bottom:1.25rem;">Enterate de nuevas flores, promociones y fechas especiales.</p>
        <form method="POST" action="{{ route('cuenta.newsletter.update') }}">
            @csrf
            @method('PUT')
            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-size:0.92rem;">
                <input type="checkbox" name="newsletter" value="1" {{ $cliente->activo ? 'checked' : '' }} style="margin-top:3px;width:18px;height:18px;accent-color:var(--verde);flex-shrink:0;">
                <span>Quiero recibir novedades, promociones y temporadas de {{ config('floristeria.nombre') }} en mi correo.</span>
            </label>
            <button type="submit" class="c-btn c-btn-primary" style="margin-top:1.25rem;">Guardar preferencia</button>
        </form>
    </div>

</div>
@endsection
