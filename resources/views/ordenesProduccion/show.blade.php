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

        .btn-surtir-orden {
            margin-left: 400px;
        }

        .btn-guardar-surtido {
            margin-left: 450px;
        }

        .btn-cancelar-surtido {
            margin-right: 500px;
        }

        .btn-cerrar-orden {
            margin-right: 400px;
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
        <a href="{{ route('ordenesProduccion.index') }}" class="btn btn-secondary">Volver</a>

        <br><br>

        <h2>DATOS GENERALES DE LA ORDEN DE PRODUCCION</h2>

        <!-- Datos generales de la orden -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>No. Orden</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $orden->usuario }}</td>
                    <td>{{ $orden->noOrden }}</td>
                    <td>{{ $orden->fecha }}</td>
                </tr>
            </tbody>
        </table>

        <div class="row text-center">
            <div class="col-md-6 mt-4">
                <a id="surtirOrdenBtn" class="btn btn-success btn-surtir-orden">Surtir Orden</a>
            </div>
            <div class="col-md-6 mt-4">
                <a id="cerrarOrdenBtn" href="{{ route('ordenesProduccion.cerrarOrden', $orden->id) }}" class="btn btn-danger btn-cerrar-orden">Cerrar Orden</a>
            </div>
        </div>
    
        <br><br>

        <!-- Detalles de la orden -->
        <h3>Detalles de la Orden</h3>

        <table id="ordenProduccionTable" class="table table-sm">
            <thead>
                <tr>
                    <th>Código MP</th>
                    <th>Descripción 1</th>
                    <th>Requerido</th>
                    <th>UM</th>
                    <th>Entregado</th>
                    <th class="editable">Surtir</th>
                    <th>Pendiente</th>
                    <th class="opciones-column">Devuelto</th>
                    <th class="opciones-column">Motivo Devolución</th>
                    <th class="opciones-column">Opciones</th>
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
                        <!-- Utiliza contenteditable para hacer editable el campo "Entregado" -->
                        <td contenteditable="false" class="editable" data-detalle-id="{{ $detalle->id }}"></td>
                        <td class="text-right">{{ $detalle->pendiente }}</td>
                        <td class="opciones-column text-right">{{ $detalle->devuelto }}</td>
                        <td class="opciones-column">{{ $detalle->motivo_devolucion }}</td>
                        <td class="opciones-column">
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

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Espera a que el documento esté completamente cargado
        $(document).ready(function() {
            var cerrarOrdenBtn = $('#cerrarOrdenBtn');

            cerrarOrdenBtn.click(function(e) {
                e.preventDefault();

                var confirmacion = confirm("¿Estás seguro de cerrar la orden?");

                if (confirmacion) {
                    window.location.href = cerrarOrdenBtn.attr('href');
                } else {
                    // No hay necesidad de un bloque else aquí
                }
            });
        });
    </script>

    <script>
        // Espera a que el documento esté completamente cargado
        $(document).ready(function () {
            // Oculta las celdas de la columna "Opciones"
            $('.editable').hide();

            $('#surtirOrdenBtn').hide();

            // Verificar si algún valor de $detalle->entregado es mayor a 0
            var mostrarSurtirOrden = false;

            $('#ordenProduccionTable tbody tr').each(function () {
                var pendiente = parseFloat($(this).find('td:eq(6)').text()); // Obtiene el valor de "Pendiente" en la sexta columna

                if (pendiente !== 0) {
                    mostrarSurtirOrden = true;
                    return;
                }
            });

            // Mostrar u ocultar el botón "Surtir Orden" según el resultado
            if (mostrarSurtirOrden) {
                $('#surtirOrdenBtn').show();
            } else {
                $('#surtirOrdenBtn').hide();
            }

            // Agrega evento de clic al botón Surtir Orden
            $('#surtirOrdenBtn').click(function () {
                // Lógica para determinar cuáles campos "Entregado" deben ser editables
                $('#ordenProduccionTable tbody tr').each(function () {
                    var pendiente = parseFloat($(this).find('td:eq(6)').text()); // Obtiene el valor de "Pendiente" en la sexta columna

                    if (pendiente !== 0) {
                        // Hace que el campo "Entregado" sea editable solo si "Pendiente" es diferente de 0
                        var editableField = $(this).find('.editable');
                        editableField.attr('contenteditable', 'true');
                        editableField.css({
                            'background-color': '#C8FFBD',
                            'border': '1px solid #4CAF50'
                        });

                        // Agrega un manejador de evento keydown para la tecla TAB
                        editableField.keydown(function (e) {
                            if (e.which === 9) { // Código de tecla TAB
                                e.preventDefault(); // Evita que la tecla TAB realice su acción predeterminada (cambiar de foco)

                                // Encuentra el próximo campo editable y selecciona su contenido
                                var nextEditable = $(this).closest('tr').nextAll('tr:has(.editable)').first().find('.editable');
                                if (nextEditable.length > 0) {
                                    nextEditable.focus();
                                    var range = document.createRange();
                                    range.selectNodeContents(nextEditable[0]);
                                    var selection = window.getSelection();
                                    selection.removeAllRanges();
                                    selection.addRange(range);
                                }
                            }
                        });

                        // Oculta las celdas de la columna "Opciones"
                        $('.editable').show();
                    }
                });

                // Oculta las celdas de la columna "Opciones"
                $('.opciones-column').hide();

                // Agrega los botones "Guardar Surtido" y "Volver"
                var surtirOrdenButtons = `
                        <div class="row text-center">
                            <div class="col-md-6 mt-4">
                                <button id="guardarSurtidoBtn" class="btn btn-success btn-guardar-surtido">Guardar</button>
                            </div>
                            <div class="col-md-6 mt-4">
                            <button id="volverBtn" class="btn btn-primary btn-cancelar-surtido">Cancelar</button>
                            </div>
                        </div>
                    `;
                $('.row.text-center').html(surtirOrdenButtons);

                // Agrega evento de clic al botón "Guardar Surtido"
                $('#guardarSurtidoBtn').click(function () {
                    // Lógica para validar y guardar el surtido mediante una solicitud AJAX
                    if (validarSurtido()) {
                        guardarSurtido();
                    } else {
                        alert("Por favor, ingrese valores válidos para el campo 'Entregado'.");
                    }
                });

                function validarSurtido() {
                    var isValid = true;

                    // Recorre todas las filas de la tabla
                    $('#ordenProduccionTable tbody tr').each(function () {
                        var entregadoInput = $(this).find('.editable');
                        var entregado = entregadoInput.text().trim(); // Obtiene el valor del campo "Entregado" y elimina espacios en blanco al inicio y al final
                        var pendiente = parseFloat($(this).find('td:eq(6)').text());

                        // Validaciones solo si el campo "Entregado" no está vacío
                        if (entregado !== '') {
                            if (isNaN(entregado) || entregado < 1 || entregado > (pendiente + 1)) {
                                entregadoInput.css({
                                    'background-color': '#FFB6C1',  // Cambia el color de fondo a rojo claro en caso de error
                                    'border': '1px solid #FF0000'
                                });
                                isValid = false;
                            } else {
                                entregadoInput.css({
                                    'background-color': '#C8FFBD',  // Cambia el color de fondo a verde claro en caso de éxito
                                    'border': '1px solid #4CAF50'
                                });
                            }
                        } else {
                            // Si el campo "Entregado" está vacío, no se aplica ningún estilo
                            if(entregadoInput.css('background-color') !== 'rgb(200, 255, 189)'){
                                entregadoInput.css({
                                    'background-color': '',
                                    'border': ''
                                });
                            }
                        }
                    });

                    return isValid;
                }

                // Agrega evento de clic al botón "Volver"
                $('#volverBtn').click(function () {
                    // Recargar la página
                    location.reload();
                });

                // Aquí puedes manejar la lógica para surtir la orden
            });

            function guardarSurtido() {
                // Array para almacenar detalles
                var detalles = [];

                // Recorre todas las filas de la tabla
                $('#ordenProduccionTable tbody tr').each(function () {
                    // Obtiene el detalleId y nuevoValor de cada fila
                    var detalleId = $(this).find('.editable').data('detalle-id');
                    var nuevoEntregado = $(this).find('.editable').text();

                    // Agrega los detalles al array
                    detalles.push({ detalleId: detalleId, nuevoEntregado: nuevoEntregado });
                });

                // Crea un formulario dinámicamente
                var form = $('<form action="{{ route("ordenesProduccion.guardarSurtido") }}" method="POST"></form>');

                // Agrega el token CSRF al formulario
                form.append('{{ csrf_field() }}');

                // Agrega los detalles como campos ocultos al formulario
                for (var i = 0; i < detalles.length; i++) {
                    form.append('<input type="hidden" name="detalles[' + i + '][detalleId]" value="' + detalles[i].detalleId + '">');
                    form.append('<input type="hidden" name="detalles[' + i + '][nuevoEntregado]" value="' + detalles[i].nuevoEntregado + '">');
                }

                // Agrega el formulario al cuerpo del documento y envía la solicitud
                $('body').append(form);
                form.submit();
            }
        });
    </script>

</body>
</html>