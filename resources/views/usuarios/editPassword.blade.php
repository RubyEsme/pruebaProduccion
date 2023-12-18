<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cambiar contraseña</title>
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

        <h2>Cambiar Contraseña</h2>

        <br>

        <!-- Formulario de edición -->
        <form action="{{ route('usuarios.updatePassword', $user->id) }}" method="POST" id="passwordForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password">Confirmar contraseña:</label>
                <input type="password" class="form-control" id="password_repetida" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
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

    <!-- Script para validar contraseñas -->
    <script>
        $(document).ready(function() {
            $("#passwordForm").submit(function(event) {
                // Obtener valores de las contraseñas
                var password = $("#password").val();
                var password_repetida = $("#password_repetida").val();

                // Validar si las contraseñas coinciden
                if (password !== password_repetida) {
                    // Mostrar alerta
                    alert("Las contraseñas no coinciden");
                    // Prevenir el envío del formulario
                    event.preventDefault();
                }
            });
        });
    </script>

</body>
</html>
