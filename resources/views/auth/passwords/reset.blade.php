<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña — Comidex</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top, #fff8eb 0%, #f4f6f9 55%);
        }
        .brand-badge {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 8px 20px -6px rgba(234, 88, 12, 0.45);
        }
        .form-control {
            padding: 0.6rem 0.75rem;
            border-radius: 0.5rem;
        }
        .form-control:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 0.2rem rgba(234, 88, 12, 0.15);
        }
        .btn-brand {
            background-color: #ea580c;
            border-color: #ea580c;
            color: #fff;
            border-radius: 0.5rem;
        }
        .btn-brand:hover {
            background-color: #c2410c;
            border-color: #c2410c;
            color: #fff;
        }
    </style>
</head>
<body class="d-flex flex-column align-items-center justify-content-center min-vh-100 p-3">
    <!-- Encabezado de Marca -->
    <div class="text-center mb-4">
        <div class="brand-badge mb-2">🍽️</div>
        <h1 class="h3 fw-bold text-dark mb-0">
            Comidex <span class="fw-normal text-secondary fs-6">by Daniel</span>
        </h1>
        <p class="text-muted small mb-0">Sistema de Gestión para Restaurantes</p>
    </div>

    <!-- Tarjeta de Nueva Contraseña -->
    <div class="card border-0 shadow-sm rounded-4 p-4 w-100" style="max-width: 400px;">
        <h2 class="h5 fw-bold text-dark text-center mb-2">Nueva Contraseña</h2>
        <p class="text-muted small text-center mb-4">
            Escribe y confirma tu nueva contraseña para acceder a tu cuenta.
        </p>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2 small rounded-3" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', request('email')) }}">

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Nueva contraseña</label>
                <input type="password" name="password" required class="form-control" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required class="form-control" placeholder="Repite la contraseña">
            </div>

            <button type="submit" class="btn btn-brand w-100 fw-semibold py-2 mb-3">
                Restablecer contraseña
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                ← Volver al login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>