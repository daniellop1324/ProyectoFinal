<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Consumo — Comidex</title>
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
            Comidex <span class="fw-normal text-secondary fs-6">by Bryan</span>
        </h2>
        <small class="text-muted">Sistema de Gestión para Restaurantes</small>
    </div>

    <div class="container" style="max-width: 950px;">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <!-- Cabecera del Historial -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h4 fw-bold text-success mb-0">📜 Historial de Consumo y Gastos</h1>
                    <small class="text-muted">Revisa tus pedidos, estados y total acumulado</small>
                </div>

                <a href="{{ url('/menu') }}" class="btn btn-outline-secondary btn-sm fw-semibold px-3 text-nowrap">
                    🍔 Ir al Menú
                </a>
            </div>

            <!-- Mensajes de éxito o error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 small rounded-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2 small rounded-3" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($pedidos->isEmpty())
                <div class="text-center py-5 bg-light rounded-3 border border-light-subtle">
                    <div class="fs-2 mb-2">📜</div>
                    <p class="fw-semibold text-dark mb-1">Aún no tienes un historial de consumo registrado</p>
                    <p class="text-muted small mb-0">Cuando realices un pedido desde el menú, aparecerá aquí.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle border rounded-3 overflow-hidden mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-3"># Pedido</th>
                                <th class="py-3 px-3">Plato</th>
                                <th class="py-3 px-3">Modificaciones</th>
                                <th class="py-3 px-3">Estado</th>
                                <th class="py-3 px-3">Precio</th>
                                <th class="py-3 px-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                                @php
                                    $estadoLower = strtolower($pedido->estado);
                                @endphp
                                <tr>
                                    <td class="py-3 px-3 fw-semibold">#{{ $pedido->id }}</td>
                                    <td class="py-3 px-3 fw-medium text-dark">{{ $pedido->nombre_plato }}</td>
                                    <td class="py-3 px-3 text-secondary small">
                                        {{ $pedido->observaciones ?: 'Ninguna' }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="badge rounded-pill px-2 py-1
                                            {{ $estadoLower === 'recibido' ? 'bg-warning text-dark' : '' }}
                                            {{ $estadoLower === 'preparado' ? 'bg-primary text-white' : '' }}
                                            {{ $estadoLower === 'entregado' ? 'bg-success text-white' : '' }}">
                                            {{ ucfirst($pedido->estado) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 fw-bold text-success text-nowrap">
                                        Q{{ number_format($pedido->total, 2) }}
                                    </td>
                                    <td class="py-3 px-3 text-center text-nowrap">
                                        @if($estadoLower === 'recibido')
                                            <button
                                                type="button"
                                                class="btn btn-outline-warning btn-sm fw-semibold text-dark"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarModificacionModal"
                                                data-id="{{ $pedido->id }}"
                                                data-plato="{{ $pedido->nombre_plato }}"
                                                data-observaciones="{{ $pedido->observaciones }}"
                                                data-action="{{ route('pedido.update', $pedido->id) }}">
                                                ✏️ Editar nota
                                            </button>
                                        @else
                                            <span class="text-muted small fst-italic">Sin cambios</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Total Gastado -->
                <div class="mt-4 bg-success-subtle border border-success-subtle p-3 rounded-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span class="fw-bold text-secondary">Total Acumulado Gastado:</span>
                    <span class="h4 fw-bold text-success mb-0">Q{{ number_format($totalGastado, 2) }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para editar únicamente las modificaciones del pedido -->
    <div class="modal fade" id="editarModificacionModal" tabindex="-1" aria-labelledby="editarModificacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="editarModificacionLabel">
                        ✏️ Modificar observaciones — <span id="modalPedidoNumero" class="text-secondary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditarModificacion" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            Plato: <strong id="modalPedidoPlato" class="text-dark"></strong>
                        </p>
                        <div class="mb-2">
                            <label for="inputObservaciones" class="form-label fw-semibold small text-secondary">
                                Modificaciones (¿Agregar o quitar algo?)
                            </label>
                            <input
                                type="text"
                                name="observaciones"
                                id="inputObservaciones"
                                class="form-control"
                                placeholder="Ej: Sin cebolla, extra queso">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold px-3" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-brand btn-sm fw-semibold px-3">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalEl = document.getElementById('editarModificacionModal');
        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const plato = button.getAttribute('data-plato');
                const observaciones = button.getAttribute('data-observaciones') || '';
                const action = button.getAttribute('data-action');

                document.getElementById('modalPedidoNumero').textContent = '#' + id;
                document.getElementById('modalPedidoPlato').textContent = plato;
                document.getElementById('inputObservaciones').value = observaciones;
                document.getElementById('formEditarModificacion').setAttribute('action', action);
            });
        }
    </script>
</body>
</html>