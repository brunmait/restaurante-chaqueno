@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fa fa-user-edit me-2"></i>Editar Cajero</h3>
    <a class="btn btn-secondary" href="{{ route('admin.cajeros.index') }}">Volver a Lista</a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Datos del Cajero</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cajeros.update', $cajero->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $cajero->nombre) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $cajero->email) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6">
                            <div class="form-text">Dejar en blanco para mantener la contraseña actual</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ ($cajero->activo ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">
                                Usuario activo (puede iniciar sesión)
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>Actualizar Cajero
                        </button>
                        <a href="{{ route('admin.cajeros.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times me-1"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Información del Cajero</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID:</strong> {{ $cajero->id }}<br>
                    <strong>Estado actual:</strong> 
                    @if(($cajero->activo ?? 1))
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactivo</span>
                    @endif
                </div>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Si cambias la contraseña, el cajero deberá usar la nueva contraseña para acceder.
                </div>
                <ul class="list-unstyled small text-muted">
                    <li><i class="fa fa-check text-success me-1"></i> Acceso al panel de cajero</li>
                    <li><i class="fa fa-check text-success me-1"></i> Gestión de ventas</li>
                    <li><i class="fa fa-check text-success me-1"></i> Impresión de recibos</li>
                    <li><i class="fa fa-check text-success me-1"></i> Atención de pedidos en línea</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
