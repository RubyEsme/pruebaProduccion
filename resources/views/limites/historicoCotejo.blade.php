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

    <title>Limites Historico - Index</title>
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

        <h2>Listado de Limites VS Cotejo Histórico</h2>

        <br>
        
        <a href="{{ route('limites.indexCotejo') }}" class="btn btn-secondary">Volver</a>

        <br><br>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="selectDescripcion"><b>Descripción 1:</b></label>
                <select class="form-control" id="selectDescripcion">
                    <!-- Opciones de descripción ordenadas alfabéticamente se agregarán con JavaScript -->
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="selectStatus"><b>Status</b></label>
                <select class="form-control" id="selectStatus">
                    <!-- Opciones de descripción ordenadas alfabéticamente se agregarán con JavaScript -->
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="selectMes"><b>Mes:</b></label>
                <select class="form-control" id="selectMes">
                    <!-- Opciones de mes ordenadas correctamente se agregarán con JavaScript -->
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="selectAño"><b>Año:</b></label>
                <select class="form-control" id="selectAño">
                    <!-- Opciones de año ordenadas de forma ascendente se agregarán con JavaScript -->
                </select>
            </div>
        </div>

        <!-- Tabla con las limites -->
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CODIGO MP</th>
                    <th>DESCRIPCION 1</th>
                    <th>MES</th>
                    <th>AÑO</th>
                    <th>LIMITE</th>
                    <th>ENTREGADO</th>
                    <th>% USO</th>
                    <th>Status</th>
                    <th>Diferencia (KG)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($limites as $limite)
                    <tr>
                        <td>{{ $limite->id }}</td>
                        <td>{{ $limite->codigo_mp }}</td>
                        <td>{{ $limite->descripcion_1 }}</td>
                        <td>{{ $limite->mes }}</td>
                        <td>{{ $limite->año }}</td>
                        <td class="text-right">{{ number_format($limite->limite, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($limite->entregado, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($limite->porcentaje_uso, 2, '.', ',') }}</td>
                        <td>{{ $limite->status }}</td>
                        <td class="text-right">{{ number_format($limite->kg_diferencia, 2, '.', ',') }}</td>
                        <!-- Agrega las demás columnas según los campos -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

    </div>

    <br>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <script>
        $(document).ready(function() {
            // Obtener las opciones únicas para mes, año y descripción desde los datos de la tabla
            var descripciones = <?php echo json_encode(array_merge([""], array_values(array_unique($limites->pluck('descripcion_1')->sort()->toArray())))); ?>;
            var status = <?php echo json_encode(array_merge([""], array_values(array_unique($limites->pluck('status')->sort()->toArray())))); ?>;
            var meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            var años = <?php echo json_encode(array_merge([""], array_values(array_unique($limites->pluck('año')->sort()->toArray())))); ?>;

            // Llenar el select de descripción
            var selectDescripcion = $('#selectDescripcion');
            descripciones.forEach(function(descripcion) {
                selectDescripcion.append('<option value="' + descripcion + '">' + (descripcion === "" ? "Ningún filtro" : descripcion) + '</option>');
            });

            // Llenar el select de status
            var selectStatus = $('#selectStatus');
            status.forEach(function(status) {
                selectStatus.append('<option value="' + status + '">' + (status === "" ? "Ningún filtro" : status) + '</option>');
            });

            // Llenar el select de mes
            var selectMes = $('#selectMes');
            meses.forEach(function(mes) {
                selectMes.append('<option value="' + mes + '">' + (mes === "" ? "Ningún filtro" : mes) + '</option>');
            });

            // Llenar el select de año
            var selectAño = $('#selectAño');
            años.forEach(function(año) {
                selectAño.append('<option value="' + año + '">' + (año === "" ? "Ningún filtro" : año) + '</option>');
            });

            // Inicializar DataTable
            var table = $('#example').DataTable();

            // Manejar el evento de cambio en el select de descripción
            selectDescripcion.on('change', function() {
                var descripcionSeleccionada = $(this).val();

                // Filtrar la tabla por descripción seleccionada con búsqueda exacta
                table.columns(2).search(descripcionSeleccionada, false, true).draw();
            });

            // Manejar el evento de cambio en el select de descripción
            selectStatus.on('change', function() {
                var statusSeleccionado = $(this).val();

                // Filtrar la tabla por descripción seleccionada con búsqueda exacta
                table.columns(8).search(statusSeleccionado, false, true).draw();
            });

            // Manejar el evento de cambio en el select de mes
            selectMes.on('change', function() {
                var mesSeleccionado = $(this).val();

                // Filtrar la tabla por mes seleccionado con búsqueda exacta
                table.columns(3).search(mesSeleccionado, false, true).draw();
            });

            // Manejar el evento de cambio en el select de año
            selectAño.on('change', function() {
                var añoSeleccionado = $(this).val();

                // Filtrar la tabla por año seleccionado con búsqueda exacta
                table.columns(4).search(añoSeleccionado, false, true).draw();
            });
        });
    </script>

</body>
</html>
