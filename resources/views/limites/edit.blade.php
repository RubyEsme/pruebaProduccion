<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Limite</title>
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

        <h2>Editar Limite</h2>

        <br>

        <!-- Formulario de edición -->
        <form action="{{ route('limites.update', $limite->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="codigo_mp">Código MP:</label>
                <input type="text" class="form-control" value="{{ $limite->codigo_mp }}" readonly>
            </div>

            <div class="form-group">
                <label for="descripcion_1">Descripción 1:</label>
                <input type="text" class="form-control" value="{{ $limite->descripcion_1 }}" readonly>
            </div>

            <div class="form-group">
                <label for="mes">Mes:</label>
                <?php
                    $nombre_mes = "";
                    if ($limite->mes == 1) {
                        $nombre_mes = "Enero";
                    } elseif ($limite->mes == 2) {
                        $nombre_mes = "Febrero";
                    } elseif ($limite->mes == 3) {
                        $nombre_mes = "Marzo";
                    } elseif ($limite->mes == 4) {
                        $nombre_mes = "Abril";
                    } elseif ($limite->mes == 5) {
                        $nombre_mes = "Mayo";
                    } elseif ($limite->mes == 6) {
                        $nombre_mes = "Junio";
                    } elseif ($limite->mes == 7) {
                        $nombre_mes = "Julio";
                    } elseif ($limite->mes == 8) {
                        $nombre_mes = "Agosto";
                    } elseif ($limite->mes == 9) {
                        $nombre_mes = "Septiembre";
                    } elseif ($limite->mes == 10) {
                        $nombre_mes = "Octubre";
                    } elseif ($limite->mes == 11) {
                        $nombre_mes = "Noviembre";
                    } elseif ($limite->mes == 12) {
                        $nombre_mes = "Diciembre";
                    }
                ?>
                <input type="text" class="form-control" value="{{ $nombre_mes }}" readonly>
            </div>

            <div class="form-group">
                <label for="año">Año:</label>
                <input type="number" class="form-control" value="{{ $limite->año }}" readonly>
            </div>

            <div class="form-group">
                <label for="limite">Limite:</label>
                <input type="number" class="form-control" id="limite" name="limite" value="{{ $limite->limite }}" min="1" required>
            </div>

            <br>
            
            <button type="submit" class="btn btn-primary">Actualizar</button>

            <a href="{{ route('limites.index') }}" class="btn btn-secondary">Volver</a>
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
