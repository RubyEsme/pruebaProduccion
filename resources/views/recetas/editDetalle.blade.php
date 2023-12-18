<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Editar Detalle Receta</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('welcome') }}">
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

        <h2>Editar Material</h2>

        <br>

        <!-- Formulario de edición -->
        <form id="editForm" action="{{ route('recetas.updateDetalle', ['detalle' => $detalle->id, 'idReceta' => $idReceta]) }}" method="POST">
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
                        <label for="cantidad"><b>Cantidad (GR)</b></label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" value="{{ $detalle->cantidad }}" min="0.0001" step="any">
                    </div>

                    <div class="form-group">
                        <label for="observaciones"><b>Observaciones</b></label>
                        <input type="text" class="form-control" id="observaciones" name="observaciones" value="{{ $detalle->observaciones }}">
                    </div>
                </div>
            </div>

            <br>

            <button type="submit" class="btn btn-primary">Actualizar</button>

            <a href="{{ route('recetas.edit', $idReceta) }}" class="btn btn-secondary">Volver</a>
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

</body>
</html>
