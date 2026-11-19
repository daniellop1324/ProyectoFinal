<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Entrega — Comidex</title>
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
        .entrega-card {
            border-left: 4px solid #0d6efd !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .entrega-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.35rem 0.85rem rgba(0, 0, 0, 0.06) !important;
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

    <div class="container" style="max-width: 850px;">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <!-- Cabecera de Entrega -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h4 fw-bold text-primary mb-0">🍽️ Pedidos Listos para Entregar</h1>
                    <small class="text-muted">Platos preparados en cocina pendientes de entrega a mesa</small>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('entrega.index') }}" class="btn btn-primary btn-sm fw-semibold px-3 text-nowrap">
                        📋 Mi Vista
                    </a>
                    <a href="{{ url('/cocina') }}" class="btn btn-outline-danger btn-sm fw-semibold px-3 text-nowrap">
                        🍳 Ver Cocina
                    </a>
                    <a href="{{ url('/menu') }}" class="btn btn-outline-secondary btn-sm fw-semibold px-3 text-nowrap">
                        🍔 Ir al Menú
                    </a>

                    <!-- Botón de Cerrar Sesión -->
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold px-3 text-nowrap">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mensaje de éxito -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 small rounded-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <!-- Lista de Pedidos Listos -->
            @if($pedidos->isEmpty())
                <div class="text-center py-5 bg-light rounded-3 border border-light-subtle">
                    <div class="fs-2 mb-2">🍽️</div>
                    <p class="fw-semibold text-dark mb-1">No hay platos listos para entregar en este momento</p>
                    <p class="text-muted small mb-0">Cuando cocina marque un plato como preparado, aparecerá aquí.</p>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-semibold text-secondary">
                        Listos para entregar: <span class="badge bg-primary rounded-pill">{{ $pedidos->count() }}</span>
                    </span>
                </div>

                <div class="vstack gap-3">
                    @foreach($pedidos as $pedido)
                        <div class="card entrega-card border border-light-subtle bg-white rounded-3 p-3 shadow-sm">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                                            Pedido #{{ $pedido->id }}
                                        </span>
                                        <span class="fw-bold text-success small">
                                            Q{{ number_format($pedido->total, 2) }}
                                        </span>
                                    </div>

                                    <h3 class="h5 fw-bold text-dark mb-2">{{ $pedido->nombre_plato }}</h3>

                                    <div class="small">
                                        <span class="text-secondary fw-semibold">Modificaciones:</span>
                                        @if(!empty($pedido->observaciones))
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle ms-1 px-2 py-1 fw-semibold">
                                                {{ $pedido->observaciones }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic ms-1">Ninguna (Receta estándar)</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Botón para marcar como entregado -->
                                <form action="{{ route('pedido.entregar', $pedido->id) }}" method="POST" class="m-0 flex-shrink-0">
                                    @csrf
                                    <button type="submit" class="btn btn-primary fw-semibold btn-sm px-3 py-2 w-100 text-nowrap">
                                        Marcar como Entregado 🚀
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>