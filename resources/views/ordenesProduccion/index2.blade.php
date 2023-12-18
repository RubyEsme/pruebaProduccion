<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="">

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">

    <title>Órdenes de Producción - Index</title>

    <style>
        .son{
            margin-left: 170px;
        }
    </style>
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
        @if(Auth::user()->hasRole('admin'))
            <br>
            <a href="{{ route('welcome') }}" class="btn btn-secondary">Volver</a>
            <br>
        @endif

        <br>

        <div class="row">
            
            <h2>Listado de Órdenes de Producción Surtidas/Cerradas</h2>

            <a href="{{ route('ordenesProduccion.index') }}" class="btn btn-primary son">Ver Órdenes Pendientes</a>

        </div>

        

        <br>

        <table id="example" class="table table-striped" style="width:100%">

            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>No. Orden</th>
                    <th>Fecha</th>
                    <th>Kg Requeridos</th>
                    <th>Kg Entregados</th>
                    <th>Kg Pendientes</th> 
                    <th>Kg Devueltos</th>
                    <th>Status</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tbody>

            @foreach($ordenesProduccion->unique('noOrden') as $orden)
                <tr>
                    <td>{{ $orden->usuario }}</td>
                    <td>{{ $orden->noOrden }}</td>
                    <td>{{ $orden->fecha }}</td>
                    <td class="text-right">{{ number_format($sumas[$orden->noOrden]['kg_requeridos'], 2, '.', ',') }}</td>
                    <td class="text-right">{{ number_format($sumas[$orden->noOrden]['kg_entregados'], 2, '.', ',') }}</td>
                    <td class="text-right">{{ number_format($sumas[$orden->noOrden]['kg_pendientes'], 2, '.', ',') }}</td>
                    <td class="text-right">{{ number_format($sumas[$orden->noOrden]['kg_devueltos'], 2, '.', ',') }}</td>
                    <td>{{ $orden->status }}</td>
                    <td>
                        <a href="{{ route('ordenesProduccion.show2', $orden->id) }}" class="btn btn-success">Mostrar</a>
                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>

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
        new DataTable('#example');
    </script>

</body>

</html>
