<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Plato — Comidex</title>
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
        .form-control,
        .input-group-text {
            padding: 0.6rem 0.75rem;
            border-radius: 0.5rem;
        }
        .input-group > .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group > .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
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

    <!-- Tarjeta de Nuevo Plato -->
    <div class="card border-0 shadow-sm rounded-4 p-4 w-100" style="max-width: 500px;">
        <h2 class="h5 fw-bold text-dark text-center mb-4">Agregar Nuevo Plato</h2>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2 small rounded-3" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form action="{{ route('comida.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Nombre del plato</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required class="form-control" placeholder="Ej: Pizza Hawaiana">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Precio (Q)</label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold text-secondary bg-light">Q</span>
                    <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio') }}" required class="form-control" placeholder="0.00">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Categoría</label>
                <input type="text" name="categoria" value="{{ old('categoria') }}" required class="form-control" placeholder="Ej: Hamburguesas, Bebidas">
            </div>

            <button type="submit" class="btn btn-brand w-100 fw-semibold py-2 mb-3">
                Guardar plato
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('menu') }}" class="text-decoration-none small text-muted">
                ← Volver al menú
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>