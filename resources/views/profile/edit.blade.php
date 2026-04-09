@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold" style="color: #2ec2c3;">Mi Perfil</h2>
        <hr style="border-color: #1f2937;">
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100" style="background-color: #111827; border: 1px solid #1f2937 !important;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1" style="color: #2ec2c3;">Información del Perfil</h4>
                <p class="text-muted mb-4">Actualiza la información de tu cuenta y tu dirección de correo electrónico.</p>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label text-light">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" style="background-color: #0f172a; border-color: #334155; color: #fff;">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label text-light">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" style="background-color: #0f172a; border-color: #334155; color: #fff;">
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn fw-bold px-4 shadow-sm" style="background-color: #2ec2c3; color: #000;">Guardar Cambios</button>

                    @if (session('status') === 'profile-updated')
                        <span class="text-success ms-3 fw-bold">¡Guardado!</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100" style="background-color: #111827; border: 1px solid #1f2937 !important;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1" style="color: #2ec2c3;">Actualizar Contraseña</h4>
                <p class="text-muted mb-4">Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.</p>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label text-light">Contraseña Actual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" style="background-color: #0f172a; border-color: #334155; color: #fff;">
                        @error('current_password', 'updatePassword') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-light">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" style="background-color: #0f172a; border-color: #334155; color: #fff;">
                        @error('password', 'updatePassword') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-light">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" style="background-color: #0f172a; border-color: #334155; color: #fff;">
                        @error('password_confirmation', 'updatePassword') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn fw-bold px-4 shadow-sm" style="background-color: #2ec2c3; color: #000;">Actualizar Contraseña</button>

                    @if (session('status') === 'password-updated')
                        <span class="text-success ms-3 fw-bold">¡Actualizada!</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 mb-5">
        <div class="card shadow-sm border-0" style="background-color: #111827; border: 1px solid #1f2937 !important;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1 text-danger">Eliminar Cuenta</h4>
                <p class="text-muted mb-4">Una vez que se elimine la cuenta, todos sus recursos y datos se borrarán permanentemente.</p>

                <button type="button" class="btn btn-outline-danger fw-bold px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    Eliminar Cuenta
                </button>

                <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: #1f2937; color: #fff; border: 1px solid #374151;">
                            <form method="post" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold" id="deleteAccountModalLabel">¿Eliminar cuenta?</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body border-0">
                                    <p class="text-muted">Ingresa la contraseña para confirmar la eliminación permanente de los datos.</p>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required style="background-color: #0f172a; border-color: #334155; color: #fff;">
                                        @error('password', 'userDeletion') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger fw-bold">Confirmar Eliminación</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection