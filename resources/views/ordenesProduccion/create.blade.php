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

    <style>
        /* Agrega tu propio estilo aquí, si es necesario */
        .custom-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .custom-table {
            width: 50%; /* Ajusta según sea necesario */
            margin-bottom: 20px;
        }

        .custom-table-ordenproduccion {
            width: 55%; /* Ajusta según sea necesario */
            margin-bottom: 20px;
        }

        .custom-table-extended {
            width: 120%; /* Ajusta según sea necesario */
            margin-bottom: 20px;
        }

        .button-container button{
            display: grid;
            grid-template-rows: auto auto; /* Dos filas automáticas */
            width: 100%;
            margin-left:15px;
            margin-top: 33px;
        }

        .input-container{
            width: 85%;
            display: flex;
            justify-content: space-between;

        }

        .mi-tabla-orden {
            background-color: #F0FFF1; /* Color de fondo destacado */
            border: 2px solid #000000; /* Borde destacado */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra destacada */
        }

        .mi-tabla-recetas {
            background-color: #EEFDFF; /* Color de fondo destacado */
            border: 2px solid #000000; /* Borde destacado */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra destacada */
        }

        .mi-tabla-extras {
            background-color: #EEFDFF; /* Color de fondo destacado */
            border: 2px solid #000000; /* Borde destacado */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra destacada */
        }

    </style>

    <title>Crear Orden de Produccion</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        @if(Auth::user()->hasRole('admin'))
            <a class="navbar-brand" href="{{ route('welcome') }}">
        @endif
        @if(Auth::user()->hasRole('linea','esmalte'))
            <a class="navbar-brand" href="#">
        @endif
            <img src="{{ asset('img/logo.png') }}" width="400" height="50" class="d-inline-block align-top" alt="Logo">
        </a>
        <span class="ml-auto"><b>{{ Auth::user()->name }}</b></span>
        <span class="ml-3"></span>
        <button class="btn btn-danger" onclick="location.href='{{ route('logout') }}'">Cerrar sesión</button>
        <span class="ml-3"></span>
    </nav>

    <br>

    <div class="container mt-3">
        @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('welcome') }}" class="btn btn-secondary">Volver</a>
            <br><br>
        @endif

        <!-- Parte de arriba -->
        <div class="row">
            <div class="input-container">
                <div class="col-md-3">
                <label for="modelo">Modelo:</label>
                <select class="form-control" id="modelo">
                    <option value="" selected disabled>Seleccionar modelo</option>
                    @foreach($modelos as $modelo)
                        <option value="{{ $modelo }}">{{ $modelo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="formato">Formato:</label>
                <select class="form-control" id="formato"></select>
            </div>
            <div class="col-md-3">
                <label for="planta">Planta:</label>
                <select class="form-control" id="planta"></select>
            </div>
            <div class="col-md-3">
                <label for="metros">Metros:</label>
                <input type="number" class="form-control" id="metros">
            </div>
            </div>
            

            <div class="button-container">
                <button class="btn btn-primary btn-block" onclick="agregarRecetas()">Añadir Receta</button>
                <button class="btn btn-primary btn-block" onclick="agregarExtras()">Añadir Extras</button>
            </div>
        </div>

        <hr>

        <!-- Parte de abajo derecha -->
        <div class="row">
            <div class="col-md-12">
                <h4>Orden de Producción</h4>
                
                <div class="custom-container">

                    <form class="custom-table" id="crearOrdenForm" action="/guardar-ordenes" target="_blank" method="POST" style="width:50%">

                        <!-- Tabla de orden de produccion -->
                        <div class="custom-table-ordenproduccion">

                            @csrf <!-- Agrega el token CSRF para proteger tu formulario -->

                            <!-- Botón Crear Orden -->
                            <button id="crearOrdenBtn" class="button btn-success" type="submit" style="width: 150%; height: 45px;"><strong>Crear Orden de Producción</strong></button>

                            <table id="recetasTableSuma" class="table table-sm mi-tabla-orden" style="width:150%">
                                <thead>
                                    <tr>
                                        <th>Código MP</th>
                                        <th>Descripción 1</th>
                                        <th>Cantidad Total (KG)</th>
                                        <th>UM</th>
                                    </tr>
                                </thead>
                                <tbody id="recetasBodySuma"></tbody>
                            </table>

                            <select id="selectPlanta" class="form-control mi-tabla-orden" id="modelo" required>
                                <option value="" selected disabled>Seleccionar Planta</option>
                                    <option value="1">Planta No. 1</option>
                                    <option value="3">Planta No. 3</option>
                            </select>

                        </div>

                    </form>

                    <!-- Tabla de recetas seleccionadas -->
                    <div class="custom-table">
                        <h4>Recetas Seleccionadas</h4>
                        <table id="recetasTable" class="table table-striped table-responsive mi-tabla-recetas" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Modelo</th>
                                    <th>Formato</th>
                                    <th>Planta</th>
                                    <th>Metros</th>
                                    <th>ID Receta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="recetasBody"></tbody>
                        </table>

                    <!-- Tabla de extras seleccionados -->
                    <div class="custom-table">
                        <h4>Materiales Extras</h4>
                        <table id="materialesTable" class="table table-striped mi-tabla-extras" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Codigo MP</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad (KG)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="materialesBody"></tbody>
                        </table>
                    </div>
                    </div>

                    <!-- Tabla de detalles receta -->
                    <div class="custom-table-extended">
                        <h4>Detalles de Recetas</h4>
                        <div class="search-container">
                            <input type="text" name="txtModelo" class="search-input" placeholder="Ingresa el modelo">
                        </div>
                        <table id="detallesRecetasTable" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Codigo MP</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad (KG)</th>
                                    <th>Modelo</th>
                                </tr>
                            </thead>
                            <tbody id="detallesRecetasBody"></tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <br>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var recetasSeleccionadas = [];
        var recetasSumadas = [];

        // Escuchar el evento de submit del formulario
        $('#crearOrdenForm').submit(function (event) {
            // Preguntar al usuario si desea crear la orden de producción
            var confirmacion = confirm('¿Desea crear la orden de producción?');

            if (!confirmacion) {
                // Si el usuario no confirma, detener el envío del formulario
                event.preventDefault();
                return;
            }

            event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada

            // Obtener los registros de la tabla
            var registros = obtenerRegistros();

            // Obtener la referencia de la tabla
            var recetasTable = $('#recetasTable tbody');

            // Obtener todas las filas de la tabla
            var filas = recetasTable.find('tr');

            if (filas.length > 0) {
                // Crear un objeto para almacenar la frecuencia y la posición de cada número de planta
                var frecuenciaPlantas = {};

                // Iterar sobre cada fila y obtener el número de planta desde recetasTable
                filas.each(function (index) {
                    var planta = $(this).find('td:eq(2)').text(); // Cambié el índice a 3 para reflejar que la planta está en la columna 4 de recetasTable

                    // Incrementar la frecuencia del número de planta en el objeto
                    // y guardar la posición de la primera aparición si aún no está registrado
                    if (frecuenciaPlantas[planta]) {
                        frecuenciaPlantas[planta].frecuencia++;
                    } else {
                        frecuenciaPlantas[planta] = { frecuencia: 1, posicion: index };
                    }
                });

                // Encontrar el número de planta con la frecuencia más alta
                var plantaMasFrecuente = Object.keys(frecuenciaPlantas).reduce(function (a, b) {
                    if (frecuenciaPlantas[a].frecuencia === frecuenciaPlantas[b].frecuencia) {
                        // Si tienen la misma frecuencia, elegir la que se encontró primero
                        return frecuenciaPlantas[a].posicion > frecuenciaPlantas[b].posicion ? b : a;
                    } else {
                        return frecuenciaPlantas[a].frecuencia > frecuenciaPlantas[b].frecuencia ? a : b;
                    }
                });

            }else{
                var plantaMasFrecuente = $('#selectPlanta').val();;
            }

            // Agregar plantaMasFrecuente como campo oculto al formulario
            $('<input />').attr('type', 'hidden')
                .attr('name', 'plantaMasFrecuente')
                .attr('value', plantaMasFrecuente)
                .appendTo('#crearOrdenForm');

            // Agregar los registros como un campo oculto al formulario
            $('<input />').attr('type', 'hidden')
                .attr('name', 'registros')
                .attr('value', JSON.stringify(registros))
                .appendTo('#crearOrdenForm');

            // Mostrar mensaje de confirmación
            alert('Orden creada correctamente');

            // Enviar el formulario
            this.submit();
            
            location.reload();
        });

        // Función para obtener los registros de la tabla
        function obtenerRegistros() {
            var recetasTableSuma = $('#recetasTableSuma tbody');
            var filas = recetasTableSuma.find('tr');
            var registros = [];

            filas.each(function () {
                var codigo_mp = $(this).find('td:eq(0)').text();
                var descripcion_1 = $(this).find('td:eq(1)').text();
                var requerido = $(this).find('td:eq(2)').text();
                var um = $(this).find('td:eq(3)').text();

                var registro = {
                    codigo_mp: codigo_mp,
                    descripcion_1: descripcion_1,
                    requerido: requerido,
                    um: um
                };

                registros.push(registro);
            });

            return registros;
        }

        function cargarFormatos() {
            var modeloSeleccionado = $('#modelo').val();

            $.ajax({
                type: 'GET',
                url: '/obtener-formatos/' + modeloSeleccionado,
                success: function(data) {
                    $('#formato').empty();
                    $.each(data, function(index, formato) {
                        $('#formato').append('<option value="' + formato + '">' + formato + '</option>');
                    });

                    cargarPlantas();  // Llamamos directamente a cargarPlantas después de cargar los formatos
                }
            });
        }

        function cargarPlantas() {
            var modeloSeleccionado = $('#modelo').val();
            var formatoSeleccionado = $('#formato').val();

            $.ajax({
                type: 'GET',
                url: '/obtener-plantas/' + modeloSeleccionado + '/' + formatoSeleccionado,
                success: function(data) {
                    $('#planta').empty();
                    $.each(data, function(index, planta) {
                        $('#planta').append('<option value="' + planta + '">' + planta + '</option>');
                    });

                    $('#planta').prop('disabled', false);
                }
            });
        }

        $('#modelo').on('change', function() {
            cargarFormatos();
        });

        $('#formato').on('change', function() {
            cargarPlantas();
        });

        $(document).ready(function() {
            $('#descripcion_1').on('change', function () {
                var descripcionSeleccionada = $(this).val();
                var descripcionCodificada = encodeURIComponent(descripcionSeleccionada);

                $.ajax({
                    type: 'GET',
                    url: '/obtener-codigo-mp/' + descripcionCodificada,
                    success: function(data) {
                        // Asumiendo que data.codigo_mp es el valor que quieres mostrar
                        var codigo_mp = data.codigo_mp;

                        // Actualizar el contenido de #codigo_mp
                        $('#codigo_mp').text(codigo_mp);
                    },
                    error: function(error) {
                        console.error('Error al obtener la descripción_1:', error);
                    }
                });
            });
        });

        $(document).ready(function () {
            // Función para manejar el evento input en el input de búsqueda
            $("input[name='txtModelo']").on('input', function () {
                // Obtener el valor del input de búsqueda
                var modeloABuscar = $(this).val().trim().toLowerCase();

                // Filtrar la tabla de detallesRecetasTable
                $("#detallesRecetasTable tbody tr").each(function () {
                    var modeloEnFila = $(this).find("td:nth-child(4)").text().trim().toLowerCase();

                    // Mostrar u ocultar la fila según si coincide con el modelo buscado
                    if (modeloEnFila.includes(modeloABuscar)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });

        // Función para agrupar un array de objetos por múltiples claves
        function groupBy(array, keys) {
            return array.reduce(function (acc, obj) {
                var key = keys.map(key => obj[key]).join('-');
                (acc[key] = acc[key] || []).push(obj);
                return acc;
            }, {});
        }

        function agregarExtras() {
            // Aquí debes realizar una petición AJAX para obtener las descripciones desde el controlador y cargar la lista desplegable
            $.ajax({
                type: 'GET',
                url: '/obtener-descripciones',
                success: function(data) {
                    $('#descripcion_1').empty();

                    // Agregar la opción predeterminada
                    $('#descripcion_1').append('<option value="" selected disabled>Seleccionar Descripcion</option>');

                    // Agregar las opciones obtenidas
                    $.each(data, function(index, descripcion_1) {
                        $('#descripcion_1').append('<option value="' + descripcion_1 + '">' + descripcion_1 + '</option>');
                    });
                }
            });

            // Mostrar el modal
            $('#modalExtras').modal('show');
        }

        function agregarExtra() {
            // Aquí debes obtener los valores de los campos del formulario en el modal
            var codigo_mp = $('#codigo_mp').text();
            var descripcion_1 = $('#descripcion_1').val();
            var cantidad = $('#cantidad').val();

            // Validar si el material extra ya está en la tabla de materiales
            var materialExistente = $('#materialesTable tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() === codigo_mp;
            });

            if (materialExistente.length > 0) {
                alert('El material extra ya se añadió.');
                return;
            }

            if (descripcion_1 === null) {
                alert('Por favor, seleccione una descripción');
                return;
            }

            if (cantidad === '') {
                alert('Por favor, ingrese el campo Cantidad (kg)');
                return;
            }

            if (cantidad % 1 !== 0) {
                alert('Por favor, ingrese solo números enteros en el campo de cantidad.');
                return;
            }

            // Validar si la cantidad es un número y no es negativo
            if (isNaN(cantidad) || parseFloat(cantidad) <= 0) {
                alert('Por favor, ingrese una cantidad mayor o igual a 1.');
                return;
            }

            // Cerrar el modal después de añadir el extra
            $('#modalExtras').modal('hide');

            // Agregar el registro a la tabla
            agregarRegistroMaterialesTable(codigo_mp, descripcion_1, cantidad);

            // Limpiar los campos del formulario en el modal
            $('#codigo_mp').text(''); // Si '#codigo_mp' es un elemento de tipo input, usa .val('')
            $('#descripcion_1').val('');
            $('#cantidad').val('');
        }


        function agregarRegistroMaterialesTable(codigo_mp, descripcion_1, cantidad) {
            // Obtener la referencia de la tabla
            var materialesTable = $('#materialesTable tbody');
            
            // Crear una nueva fila
            var nuevaFila = `
                <tr>
                    <td>${codigo_mp}</td>
                    <td>${descripcion_1}</td>
                    <td class="text-right">${(parseFloat(cantidad)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td><button class="btn btn-danger" onclick="eliminarMaterial('${codigo_mp}', '${descripcion_1}', '${cantidad}', this)">Eliminar</button></td>
                </tr>
            `;

            // Agregar la nueva fila a la tabla
            materialesTable.append(nuevaFila);

            actualizarRecetasTableSuma(codigo_mp, descripcion_1, cantidad, null, 1);
        }

        function agregarRecetas() {
            var modelo = $('#modelo').val();
            var formato = $('#formato').val();
            var planta = $('#planta').val();
            var metros = $('#metros').val();

            // Obtener modelos existentes en recetasTable
            var modelosExisten = [];
            $('#recetasTable tbody tr').each(function () {
                var modeloExistente = $(this).find('td:eq(0)').text(); // Ajusta el índice según la posición de la columna del modelo
                modelosExisten.push(modeloExistente);
            });

            // Validar si metros contiene un punto decimal
            if (metros % 1 !== 0) {
                alert('Por favor, ingrese solo números enteros en el campo de metros.');
                return;
            }

            if (modelo && formato && planta && metros > 0) {
                // Verificar si el modelo ya existe en la tabla
                if (modelosExisten.includes(modelo)) {
                    alert('El modelo ' + modelo + ' ya se añadió.');
                    return;
                }

                // Limpiar el contenido del input de búsqueda
                $("input[name='txtModelo']").val('');

                $.ajax({
                    type: 'GET',
                    url: '/obtener-recetas/' + modelo + '/' + formato + '/' + planta,
                    data: { metros: metros },
                    success: function (data) {
                        if (data.success) {
                            data.recetas = data.recetas.filter(function (receta) {
                                return receta.cantidad !== 0;
                            });

                            data.recetas.forEach(function (receta) {
                                receta.cantidad_kg = ((receta.cantidad / 1000) * metros).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            });

                            var idreceta = data.recetas[0].idreceta;

                            // Añadir un registro a recetasTable
                            agregarRegistroRecetasTable(modelo, formato, planta, metros, idreceta);
                        } else {
                            $('#modalRecetasMultiples').modal('show');
                            var modalContent = document.getElementById('modalContent');
                            modalContent.innerHTML = '';

                            var groupedRecetas = groupBy(data.recetas, ['modelo', 'formato', 'planta', 'idreceta']);

                            for (var key in groupedRecetas) {
                                if (groupedRecetas.hasOwnProperty(key)) {
                                    var recetasGrupo = groupedRecetas[key];

                                    $('#modalModelo').text(recetasGrupo[0].modelo);
                                    $('#modalFormato').text(recetasGrupo[0].formato);
                                    $('#modalPlanta').text(recetasGrupo[0].planta);

                                    modalContent.innerHTML += `
                                        <br>
                                        <div>
                                            <h1>Receta No: ${recetasGrupo[0].idreceta}</h1>
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th class="d-none">ID de Receta</th>
                                                        <th>Código MP</th>
                                                        <th>Descripción 1</th>
                                                        <th>Cantidad</th>
                                                        <th>UM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${recetasGrupo.map(receta => `
                                                        <tr>
                                                            <td class="d-none">
                                                                <select class="form-control" id="selectReceta_${receta.idreceta}">
                                                                    <option value="${receta.idreceta}">${receta.idreceta}</option>
                                                                </select>
                                                            </td>
                                                            <td>${receta.codigo_mp}</td>
                                                            <td>${receta.descripcion_1}</td>
                                                            <td class="text-right">${((receta.cantidad / 1000) * metros).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                                            <td>KG</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                            <br>
                                            <button class="btn btn-primary" onclick="agregarRegistroRecetasTable('${recetasGrupo[0].modelo}', '${recetasGrupo[0].formato}', '${recetasGrupo[0].planta}', ${metros}, '${recetasGrupo[0].idreceta}')">Seleccionar receta ${recetasGrupo[0].idreceta}</button>
                                        </div>
                                        <br>
                                    `;
                                }
                            }
                        }
                    },
                    error: function (error) {
                        console.error('Error al obtener recetas:', error);
                    }
                });

                // Realizar la búsqueda con el valor vacío
                buscarPorModelo('');

            } else {
                alert('Por favor, seleccione modelo, formato, planta y asegúrese de que la cantidad de metros sea mayor a 0.');
            }
        }

        function buscarPorModelo(modeloABuscar) {
            // Filtrar la tabla de detallesRecetasTable
            $("#detallesRecetasTable tbody tr").each(function () {
                var modeloEnFila = $(this).find("td:nth-child(4)").text().trim().toLowerCase();

                // Mostrar u ocultar la fila según si coincide con el modelo buscado
                if (modeloEnFila.includes(modeloABuscar)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function agregarRegistroRecetasTable(modelo, formato, planta, metros, idreceta) {
            // Obtener la referencia de la tabla
            var recetasTable = $('#recetasTable tbody');

            // Crear una nueva fila
            var nuevaFila = `
                <tr>
                    <td>${modelo}</td>
                    <td>${formato}</td>
                    <td>${planta}</td>
                    <td class="text-right">${(parseFloat(metros)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${idreceta}</td>
                    <td><button class="btn btn-danger" onclick="eliminarReceta('${modelo}', '${formato}', '${planta}', ${metros}, '${idreceta}', this)">Eliminar</button></td>
                </tr>
            `;

            // Agregar la nueva fila a la tabla
            recetasTable.append(nuevaFila);

            // Cerrar el modal después de seleccionar una receta
            $('#modalRecetasMultiples').modal('hide');

            //Este ajax se va a repetir el numero de registros que tenga recetasTable
            $.ajax({
                type: 'GET',
                url: '/obtener-recetas-por-id/' + modelo + '/' + formato + '/' + planta + '/' + idreceta,
                data: { metros: metros },
                success: function (data) {
                    if (data.success) {

                        data.recetas = data.recetas.filter(function (receta) {
                            return receta.cantidad !== 0;
                        });

                        // Recorrer las recetas obtenidas y actualizar recetasTableSuma
                        for (var i = 0; i < data.recetas.length; i++) {
                            var receta = data.recetas[i];

                            // Calcular la cantidad ajustada con metros
                            var cantidadAjustada = (receta.cantidad / 1000) * metros;

                            // Actualizar recetasTableSuma o agregar nuevo registro
                            actualizarRecetasTableSuma(receta.codigo_mp, receta.descripcion_1, cantidadAjustada, modelo, 0);
                        }
                    } else {
                        console.error('Error al obtener recetas:', data.message);
                    }
                },
                error: function (error) {
                    console.error('Error al obtener recetas:', error);
                }
            });
        }

        function actualizarRecetasTableSuma(codigoMp, descripcion1, cantidad, modelo, flag) {
            // Verificar si el código_mp ya existe en recetasTableSuma
            var codigoMpExistente = $('#recetasTableSuma tbody td:first-child').filter(function() {
                return $(this).text() === codigoMp;
            });

            if(flag === 0){
                var nuevaReceta = `
                    <tr>
                        <td>${codigoMp}</td>
                        <td>${descripcion1}</td>
                        <td class="text-right">${cantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td>${(modelo)}</td>
                    </tr>
                `;

                // Agregar la nueva fila a detallesRecetasTable
                $('#detallesRecetasTable tbody').append(nuevaReceta);
            }

            if (codigoMpExistente.length > 0) {
                // Si ya existe, sumar la cantidad nueva a la existente

                //Validar si se se esta agregando un material extra o una receta, si es 0 es receta
                if(flag === 0){
                    var cantidadExistente = parseFloat(codigoMpExistente.next().next().text().replace(',', '')); // Eliminar comas y convertir a número
                    var nuevaCantidad = cantidadExistente + cantidad;
                    codigoMpExistente.next().next().text(nuevaCantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                }else{

                    var cantidadExistente = parseFloat(codigoMpExistente.next().next().text().replace(',', '')); // Eliminar comas y convertir a número
                    var nuevaCantidad = cantidadExistente + cantidad*1;
                    codigoMpExistente.next().next().text(nuevaCantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                }
            } else {
                if(flag === 0){
                    // Si no existe, agregar un nuevo registro a recetasTableSuma
                    var nuevaFilaSuma = `
                        <tr>
                            <td>${codigoMp}</td>
                            <td>${descripcion1}</td>
                            <td class="text-right">${cantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>KG</td>
                        </tr>
                    `;
                }else{
                    // Si no existe, agregar un nuevo registro a recetasTableSuma
                    var nuevaFilaSuma = `
                        <tr>
                            <td>${codigoMp}</td>
                            <td>${descripcion1}</td>
                            <td class="text-right">${(parseFloat(cantidad)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>KG</td>
                        </tr>
                    `;  
                }

                // Agregar la nueva fila a recetasTableSuma
                $('#recetasTableSuma tbody').append(nuevaFilaSuma);
            }
        }

        function eliminarMaterial(codigo_mp, descripcion_1, cantidad, button) {
            // Obtener la referencia de la tabla
            var materialesTable = $('#materialesTable tbody');

            // Obtener la fila de la tabla
            var row = $(button).closest('tr');
        
            // Eliminar la fila de la tabla
            row.remove();

            // Restar recetasTableResta
            actualizarRecetasTableResta(codigo_mp, descripcion_1, cantidad, 0);
        }

        function eliminarReceta(modelo, formato, planta, metros, idreceta, button) {
            // Obtener la referencia de la tabla
            var recetasTable = $('#recetasTable tbody');

            // Obtener la fila de la tabla
            var row = $(button).closest('tr');
        
            // Eliminar la fila de la tabla
            row.remove();

            // Limpiar el contenido del input de búsqueda
            $("input[name='txtModelo']").val('');

            eliminarDetallesRecetasTable(modelo);

            // Realizar la búsqueda con el valor vacío
            buscarPorModelo('');

            //Este ajax se va a repetir el numero de registros que tenga recetasTable
            $.ajax({
                type: 'GET',
                url: '/obtener-recetas-por-id/' + modelo + '/' + formato + '/' + planta + '/' + idreceta,
                data: { metros: metros },
                success: function (data) {
                    if (data.success) {

                        data.recetas = data.recetas.filter(function (receta) {
                            return receta.cantidad !== 0;
                        });

                        // Recorrer las recetas obtenidas y actualizar recetasTableSuma
                        for (var i = 0; i < data.recetas.length; i++) {
                            var receta = data.recetas[i];

                            // Calcular la cantidad ajustada con metros
                            var cantidadAjustada = (receta.cantidad / 1000) * metros;

                            // Restar recetasTableResta
                            actualizarRecetasTableResta(receta.codigo_mp, receta.descripcion_1, cantidadAjustada, 1);
                        }

                    } else {
                        console.error('Error al obtener recetas:', data.message);
                    }
                },
                error: function (error) {
                    console.error('Error al obtener recetas:', error);
                }
            });
        }

        function eliminarDetallesRecetasTable(modelo) {
            // Obtener la referencia de la tabla
            var detallesRecetasTable = $('#detallesRecetasTable tbody');

            // Obtener el número de filas en la tabla
            var rowCount = detallesRecetasTable.find('tr').length;

            // Hacer una iteración de 0 hasta el número de registros
            for (var i = 0; i < rowCount; i++) {
                // Obtener el modelo de la celda en la columna correspondiente
                var modeloCelda = detallesRecetasTable.find('tr').eq(i).find('td:eq(3)').text();
                // Comparar el modelo de la celda con el modelo del parámetro
                if (modeloCelda === modelo) {
                    // Eliminar la fila si el modelo coincide
                    detallesRecetasTable.find('tr').eq(i).remove();
                    // Disminuir el contador de filas ya que hemos eliminado una
                    rowCount--;
                    // Disminuir el índice para revisar la fila actual nuevamente, ya que se ha cambiado la estructura de la tabla
                    i--;
                }
            }
        }

        function actualizarRecetasTableResta(codigoMp, descripcion1, cantidad, flag) {
            // Verificar si el código_mp ya existe en recetasTableSuma
            var codigoMpExistente = $('#recetasTableSuma tbody td:first-child').filter(function() {
                return $(this).text() === codigoMp;
            });

            if (codigoMpExistente.length > 0) {

                //Validar si se se esta agregando un material extra o una receta, si es 0 es receta
                if(flag === 0){
                    var cantidadExistente = parseFloat(codigoMpExistente.next().next().text().replace(',', '')); // Eliminar comas y convertir a número
                    var nuevaCantidad = cantidadExistente - cantidad;
                    codigoMpExistente.next().next().text(nuevaCantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                }else{

                    var cantidadExistente = parseFloat(codigoMpExistente.next().next().text().replace(',', '')); // Eliminar comas y convertir a número
                    var nuevaCantidad = cantidadExistente - cantidad*1;
                    codigoMpExistente.next().next().text(nuevaCantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                }


                // Verificar si la nuevaCantidad es igual o menor a 0 y eliminar el registro
                if (nuevaCantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) <= 0.0099 || nuevaCantidad === "NaN" || $('#recetasTable tbody td').length === 0) {
                    codigoMpExistente.closest('tr').remove(); // Elimina la fila actual
                }
            } 
        }

        $(document).ready(function () {
            // Verificar y actualizar la visibilidad del botón al cargar la página
            actualizarVisibilidadBoton();

            // Función para actualizar la visibilidad del botón
            function actualizarVisibilidadBoton() {
                var recetasTableSuma = $('#recetasTableSuma tbody');
                var filas = recetasTableSuma.find('tr');

                var recetasTable = $('#recetasTable tbody');
                var filasRecetas = recetasTable.find('tr');

                var materialesTable = $('#materialesTable tbody');
                var filasMateriales = materialesTable.find('tr');

                // Si hay al menos una fila, mostrar el botón, de lo contrario, ocultarlo
                if (filas.length > 0) {
                    $('#crearOrdenBtn').show();
                } else {
                    $('#crearOrdenBtn').hide();
                }

                // Si hay al menos una fila en la tabla recetasTableSuma, ocultar el campo selectPlanta y quitar el requerimiento
                if (filasMateriales.length === 0 || filasRecetas.length > 0) {
                    $('#selectPlanta').prop('required', false).hide();
                } else {
                    // Si no hay filas, mostrar el campo selectPlanta y establecerlo como requerido
                    $('#selectPlanta').prop('required', true).show();
                }

                // Si hay al menos una fila, mostrar el botón, de lo contrario, ocultarlo
                if (filasMateriales.length > 0 && filasRecetas.length === 0) {
                    $('#selectPlanta').show();
                } else {
                    $('#selectPlanta').hide();
                }
            }

            // Escuchar cambios en la tabla y actualizar la visibilidad del botón
            $('#recetasTableSuma').on('DOMSubtreeModified', function () {
                actualizarVisibilidadBoton();
            });
        });

    </script>

<!-- Modal Recetas Multiples -->
<div class="modal fade" id="modalRecetasMultiples" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="font-size: 24px; font-weight: bold;">
                    Recetas para modelo: <span id="modalModelo"></span>, Formato: <span id="modalFormato"></span>, Planta: <span id="modalPlanta"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Aquí se mostrará la información de la receta duplicada -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Extras -->
<div class="modal fade" id="modalExtras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Extras</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formExtras">
                    <div class="form-group">
                        <label for="descripcion_1">Descripción:</label>
                        <select class="form-control" id="descripcion_1" name="descripcion_1">
                            <!-- Aquí debes cargar dinámicamente las descripcion_1 desde tu controlador -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="codigo_mp">Código MP:</label> <p id="codigo_mp"></p>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad (Kg):</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required min="0.00001">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="agregarExtra()">Añadir</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


</body>
</html>