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

    <title>OrdenProduccion - Detalles</title>
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

    <br><br>

    <!-- Contenido principal -->
    <div class="container mt-3">
        <!-- Botón de volver -->
        <a href="{{ route('ordenesProduccion.index2') }}" class="btn btn-primary">Volver</a>

        <br><br>
        
        <h2>DATOS GENERALES DE LA ORDEN DE PRODUCCION</h2>

        <!-- Datos generales de la orden -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>No. Orden</th>
                    <th>Fecha</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $orden->usuario }}</td>
                    <td>{{ $orden->noOrden }}</td>
                    <td>{{ $orden->fecha }}</td>
                    <td>{{ $orden->status }}</td>
                </tr>
            </tbody>
        </table>

        <br><br>

        <!-- Detalles de la orden -->
        <h3>Detalles de la Orden</h3>

        <table id="example" class="table">
            <thead>
                <tr>
                    <th>Código MP</th>
                    <th>Descripción 1</th>
                    <th>Requerido</th>
                    <th>UM</th>
                    <th>Entregado</th>
                    <th>Pendiente</th>
                    <th>Devuelto</th>
                    <th>Motivo Devolución</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordenProduccionDetalles as $detalle)
                    <tr>
                        <td>{{ $detalle->codigo_mp }}</td>
                        <td>{{ $detalle->descripcion_1 }}</td>
                        <td class="text-right">{{ number_format($detalle->requerido, 2, '.', ',') }}</td>
                        <td>{{ $detalle->um }}</td>
                        <td class="text-right">{{ $detalle->entregado }}</td>
                        <td class="text-right">{{ $detalle->pendiente }}</td>
                        <td class="text-right">{{ $detalle->devuelto }}</td>
                        <td>{{ $detalle->motivo_devolucion }}</td>
                        <td>
                            @if ($detalle->entregado > 0)
                                <a href="{{ route('ordenesProduccion.editDevolver', ['detalle' => $detalle->id, 'orden' => $orden->id]) }}" class="btn btn-warning">Devolver</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br><br>

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
