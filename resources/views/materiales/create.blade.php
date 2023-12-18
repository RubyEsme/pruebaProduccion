<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Crear Material</title>
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

        <h2>Nuevo Material</h2>

        <br>

        <!-- Formulario de creación -->
        <form action="{{ route('materiales.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="codigo_mp">Código MP:</label>
                <input type="text" class="form-control" id="codigo_mp" name="codigo_mp" required>
            </div>

            <div class="form-group">
                <label for="descripcion_1">Descripción 1:</label>
                <input type="text" class="form-control" id="descripcion_1" name="descripcion_1" required>
            </div>

            <br>

            <button type="submit" class="btn btn-success">Guardar</button>

            <!-- Botón de volver al índice -->
            <a href="{{ route('materiales.index') }}" class="btn btn-secondary">Volver</a>

            <br><br>
            
        </form>

        <br>
    </div>

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
