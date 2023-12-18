<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">

    <title>Recetas - Detalles</title>
    <style>
        /* Estilos personalizados */
        .table-primary>tbody>tr>td,
        .table-primary>tbody>tr>th,
        .table-primary>thead>tr>td,
        .table-primary>thead>tr>th {
            background-color: #007bff !important;
            color: #fff;
        }

        .table-primary-bordered {
            border: 2px solid #007bff;
            border-radius: 8px;
        }
    </style>
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

    <br><br>

    <!-- Contenido principal -->
    <div class="container mt-3">
        <h2>DATOS GENERALES DE LA RECETA</h2>

        <!-- Datos generales de la receta -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Formato</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Planta</th>
                    <th>Linea</th>
                    <th>ID Receta</th>
                    <th>Ficha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $receta->sku }}</td>
                    <td>{{ $receta->formato }}</td>
                    <td>{{ $receta->modelo }}</td>
                    <td>{{ $receta->tipo }}</td>
                    <td>{{ $receta->planta }}</td>
                    <td>{{ $receta->linea }}</td>
                    <td>{{ $receta->idreceta }}</td>
                    <td>{{ $receta->ficha }}</td>
                </tr>
            </tbody>
        </table>

        <br><br>

        <!-- Detalles de la receta -->
        <h3>Detalles de Receta</h3>
        <table id="example" class="table">
            <thead>
                <tr>
                    <th>Código MP</th>
                    <th>Descripción</th>
                    <th>Descripción 1</th>
                    <th>Cantidad</th>
                    <th>UM</th>
                    <th>Rodillo Digital</th>
                    <th>Observaciones</th>
                    <th>Proveedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recetasDetalles as $detalle)
                    <tr>
                        <td>{{ $detalle->codigo_mp }}</td>
                        <td>{{ $detalle->descripcion }}</td>
                        <td>{{ $detalle->descripcion_1 }}</td>
                        <td class="text-right">{{ number_format($detalle->cantidad, 2, '.', ',') }}</td>
                        <td>{{ $detalle->um }}</td>
                        <td>{{ $detalle->rodillo_digital }}</td>
                        <td>{{ $detalle->observaciones }}</td>
                        <td>{{ $detalle->proveedor }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br><br>

        <!-- Botón de volver -->
        <a href="{{ route('recetas.index') }}" class="btn btn-primary">Volver</a>
    </div>

    <br><br>

    <script>
        new DataTable('#example');
    </script>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
