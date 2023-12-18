<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <!-- Agregar enlaces a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        h1 {
            font-size: 72px; 
        }

        .btn-recetas,
        .btn-materiales,
        .btn-limites,
        .btn-cotejo,
        .btn-crear,
        .btn-eliminar {
            color: #fff;
            font-size: 24px; /* Tamaño de fuente */
            padding: 20px; /* Espaciado interno */
            margin-bottom: 10px; /* Margen inferior */
            width: 100%; /* Ancho completo */
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .btn-recetas {
            background-color: #208C29;
        }

        .btn-materiales {
            background-color: #208C29;
        }

        .btn-limites {
            background-color: #208C29;
        }

        .btn-cotejo {
            background-color: #208C29;
        }

        .btn-crear {
            background-color: #208C29;
        }

        .btn-eliminar {
            background-color: #208C29;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('img/logo.png') }}" width="400" height="50" class="d-inline-block align-top" alt="Logo">
        </a>
        <span class="ml-auto"><b>{{ Auth::user()->name }}</b></span>
        <span class="ml-3"></span>
        <button class="btn btn-danger" onclick="location.href='{{ route('logout') }}'">Cerrar sesión</button>
        <span class="ml-3"></span>
    </nav>

    <br><br><br><br>

    <div class="container">

        <div class="row">
            <div class="col">
                <div class="welcome-message text-center">
                    <h1>BIENVENIDO, {{ Auth::user()->name }}</h1>
                </div>
            </div>
        </div>

        <br><br>

        <div class="row">
            <div class="col button-grid text-center">

                @if(Auth::user()->hasRole('planeacion'))
                    <a href="{{ route('recetas.index') }}" class="btn btn-recetas">RECETAS</a>
                    <a href="{{ route('materiales.index') }}" class="btn btn-materiales">MATERIALES</a>
                    <a href="{{ route('limites.index') }}" class="btn btn-limites">LIMITES</a>
                    <a href="{{ route('limites.indexCotejo') }}" class="btn btn-cotejo">COTEJO VS LIMITE</a>
                @endif

                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('recetas.index') }}" class="btn btn-recetas">RECETAS</a>
                    <a href="{{ route('materiales.index') }}" class="btn btn-materiales">MATERIALES</a>
                    <a href="{{ route('limites.index') }}" class="btn btn-limites">LIMITES</a>
                    <a href="{{ route('limites.indexCotejo') }}" class="btn btn-cotejo">COTEJO VS LIMITE</a>
                    <a href="{{ route('registro') }}" class="btn btn-crear">CREAR USUARIOS</a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-eliminar">LISTADO USUARIOS</a>
                    <a href="{{ route('ordenesProduccion.create') }}" class="btn btn-eliminar">CREAR ORDEN DE PRODUCCIÓN</a>
                    <a href="{{ route('ordenesProduccion.index') }}" class="btn btn-eliminar">ALMACÉN</a>
                @endif
                
            </div>
        </div>

    </div>

    <br><br><br><br>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <!-- Agregar enlaces a Bootstrap JS y Popper.js (necesarios para algunos componentes de Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
