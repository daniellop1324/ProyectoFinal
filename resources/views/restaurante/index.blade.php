<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante — Comidex</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top, #fff8eb 0%, #f4f6f9 55%);
        }
        .brand-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 8px 20px -6px rgba(234, 88, 12, 0.45);
        }
        .btn-brand {
            background-color: #ea580c;
            border-color: #ea580c;
            color: #fff;
        }
        .btn-brand:hover {
            background-color: #c2410c;
            border-color: #c2410c;
            color: #fff;
        }
        .btn-delete-dish {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            flex-shrink: 0;
        }
        .form-control:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 0.2rem rgba(234, 88, 12, 0.15);
        }
    </style>
</head>
<body class="p-3 p-md-4 min-vh-100">
    <!-- Encabezado de Marca -->
    <div class="text-center mb-4">
        <div class="brand-badge mb-2">🍽️</div>
        <h2 class="h4 fw-bold text-dark mb-0">
            Comidex <span class="fw-normal text-secondary fs-6">by Daniel</span>
        </h2>
        <small class="text-muted">Sistema de Gestión para Restaurantes</small>
    </div>

    <div class="container" style="max-width: 900px;">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <!-- Cabecera con Título, Botones dinámicos según el rol y Botón de Cerrar Sesión -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h1 class="h3 fw-bold text-dark mb-0">🍔 Nuestro Menú</h1>
                
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <!-- Botón para Agregar Plato (SOLO visible para Cocinero y Mesero) -->
                    @if(auth()->user()->role === 'cocinero' || auth()->user()->role === 'mesero')
                        <a href="{{ route('comida.create') }}" class="btn btn-success btn-sm fw-semibold px-3">
                            ＋ Agregar Plato
                        </a>
                    @endif
                    
                    <!-- Botón para ir al Historial de Pedidos (SOLO para Clientes) -->
                    @if(auth()->user()->role === 'cliente')
                        <a href="{{ route('historial.index') }}" class="btn btn-outline-primary btn-sm fw-semibold px-3">
                            📜 Mi Historial
                        </a>
                    @endif

                    <!-- Botones de retorno rápido según el rol del personal -->
                    @if(auth()->user()->role === 'cocinero')
                        <a href="{{ route('cocina.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold px-3">
                            🍳 Volver a Cocina
                        </a>
                    @elseif(auth()->user()->role === 'mesero')
                        <a href="{{ route('entrega.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold px-3">
                            🍽️ Volver a Entregas
                        </a>
                    @endif

                    <!-- Botón de Cerrar Sesión -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold px-3">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mensaje de éxito si se guardó el pedido o plato -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Lista de Comidas -->
            <div class="row g-4">
                @foreach($comidas as $comida)
                    <div class="col-md-6">
                        <div class="card h-100 border shadow-sm rounded-3 p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <h3 class="h5 fw-semibold text-dark mb-0">{{ $comida->nombre }}</h3>
                                    
                                    <!-- Botón para eliminar este plato (SOLO visible para Cocinero y Mesero) -->
                                    @if(auth()->user()->role === 'cocinero' || auth()->user()->role === 'mesero')
                                        <form action="{{ route('comida.destroy', $comida->id) }}" method="POST" class="m-0 flex-shrink-0" onsubmit="return confirm('¿Estás seguro de eliminar este plato?');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-delete-dish fw-bold" title="Eliminar plato" aria-label="Eliminar plato">
                                                ×
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-success fw-bold fs-5 mt-2 mb-3">Q{{ number_format($comida->precio, 2) }}</p>
                            </div>

                            <!-- Formulario de pedido para el cliente o vista de consulta para el personal -->
                            @if(auth()->user()->role === 'cliente')
                                <form action="{{ route('pedido.guardar') }}" method="POST" class="border-top pt-3 mt-auto">
                                    @csrf
                                    <input type="hidden" name="nombre_plato" value="{{ $comida->nombre }}">
                                    <input type="hidden" name="total" value="{{ $comida->precio }}">

                                    <label class="form-label small text-secondary fw-semibold">Personaliza tu pedido (¿Agregar o quitar algo?)</label>
                                    <input type="text" name="observaciones" placeholder="Ej: Sin cebolla, extra queso" class="form-control form-control-sm mb-3">

                                    <button type="submit" class="btn btn-brand w-100 fw-semibold">
                                        Pedir este plato
                                    </button>
                                </form>
                            @else
                                <div class="border-top pt-3 mt-auto text-muted small text-center fst-italic">
                                    Vista de consulta de menú (Personal)
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>