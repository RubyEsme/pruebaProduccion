<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .resaltar {
            background-color: #ffc107; /* Cambia el color de fondo a amarillo */
            font-weight: bold; /* Hace el texto en negrita */
        }
    </style>
    <title>Devolver Material</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        @if(Auth::user()->hasRole('admin'))
            <a class="navbar-brand" href="{{ route('welcome') }}">
        @endif
        @if(Auth::user()->hasRole('almacen'))
            <a class="navbar-brand" href="{{ route('ordenesProduccion.index') }}">
        @endif
            <img src="{{ asset('img/logo.png') }}" width="400" height="50" class="d-inline-block align-top" alt="Logo">
        </a>
        <span class="ml-auto"><b>{{ Auth::user()->name }}</b></span>
        <span class="ml-3"></span>
        <button class="btn btn-danger" onclick="location.href='{{ route('logout') }}'">Cerrar sesión</button>
        <span class="ml-3"></span>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-3">

        <br>

        <h2>Devolver Material</h2>

        <br>

        <!-- Formulario de edición -->
        <form id="devolverForm" action="{{ route('ordenesProduccion.updateDevolver', ['detalle' => $detalle->id, 'orden' => $orden]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="codigo_mp">Código MP:</label>
                        <input type="text" class="form-control" value="{{ $detalle->codigo_mp }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_1">Descripción 1:</label>
                        <input type="text" class="form-control" value="{{ $detalle->descripcion_1 }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="requerido">Requerido (KG):</label>
                        <input type="number" class="form-control" value="{{ $detalle->requerido }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="devuelto"><b>Cantidad a devolver (KG)</b></label>
                        <input type="number" class="form-control" id="devuelto" name="devuelto" required min="1" max="{{ $detalle->entregado }}">
                        <!-- Agregar la validación de JavaScript -->
                        <small id="devueltoError" class="text-danger"></small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cantidadDevuelta">Devuelto (KG)</label>
                        <input type="number" class="form-control" value="{{ $detalle->devuelto }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="pendiente">Pendiente (KG)</label>
                        <input type="number" class="form-control" value="{{ $detalle->pendiente }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="cantidadEntregada" class="resaltar">Entregado (KG)</label>
                        <input type="number" class="form-control resaltar" value="{{ $detalle->entregado }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="motivo_devolucion" ><b>Motivo Devolución:</b></label>
                        <select id="motivo_devolucion" name="motivo_devolucion" class="form-control" required>
                            <option value="" disabled>Seleccione una opción</option>
                            <option value="Pedido Erróneo" {{ $detalle->motivo_devolucion === 'Pedido Erróneo' ? 'selected' : '' }}>Pedido Erróneo</option>
                            <option value="Entrega duplicada" {{ $detalle->motivo_devolucion === 'Entrega duplicada' ? 'selected' : '' }}>Entrega duplicada</option>
                            <option value="Daños en el material" {{ $detalle->motivo_devolucion === 'Daños en el material' ? 'selected' : '' }}>Daños en el material</option>
                            <option value="Cambios de producción" {{ $detalle->motivo_devolucion === 'Cambios de producción' ? 'selected' : '' }}>Cambios de producción</option>
                            <option value="Otros" {{ $detalle->motivo_devolucion === 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                    </div>

                </div>
            </div>

            <br>

            <button type="submit" class="btn btn-warning" onclick="return validarDevuelto()">Devolver</button>

            <a href="{{ route('ordenesProduccion.show', $orden) }}" class="btn btn-secondary">Volver</a>
        </form>

        <br>

    </div>

    <br>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (después de jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Validar que la cantidad a devolver no sea mayor que entregado
        function validarDevuelto() {
            var devuelto = parseFloat(document.getElementById('devuelto').value);
            var entregado = parseFloat(document.getElementById('entregado').value);

            // Limpiar el mensaje de error
            document.getElementById('devueltoError').innerHTML = '';

            // Verificar la condición
            if (devuelto > entregado) {
                // Mostrar un mensaje de error
                document.getElementById('devueltoError').innerHTML = 'La cantidad a devolver no puede ser mayor que la cantidad entregada.';
                return false;
            }

            // Devolver true para permitir que el formulario se envíe
            return true;
        }
    </script>

</body>
</html>
