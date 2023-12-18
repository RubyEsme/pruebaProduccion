<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Luego, Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>


    <title>Recetas - Editar</title>
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
        <h2>Editar Receta</h2>

        @if(session('error'))
            <!-- Alerta de error si la ficha ya existe -->
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario de edición para datos generales de la receta -->
        <form method="POST" action="{{ route('recetas.update', $receta->id) }}">
            @csrf
            @method('PUT')

            <!-- Campo oculto para enviar el array de IDs a la validación -->
            <input type="hidden" name="detallesExcluir[]" id="detallesExcluir" value="{{ implode(',', $detallesExcluir) }}">

            <table class="table table-bordered table-primary-bordered table-responsive">
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
                        <td><input type="text" name="sku" value="{{ $receta->sku }}"></td>
                        <td><input type="text" name="formato" value="{{ $receta->formato }}"></td>
                        <td><input type="text" name="modelo" value="{{ $receta->modelo }}"></td>
                        <td><input type="text" name="tipo" value="{{ $receta->tipo }}"></td>
                        <td>
                            <select name="planta">
                                <option value="1" {{ $receta->planta == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $receta->planta == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $receta->planta == '3' ? 'selected' : '' }}>3</option>
                            </select>
                        </td>
                        <td>
                            <select name="linea">
                                <option value="A" {{ $receta->linea == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $receta->linea == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ $receta->linea == 'C' ? 'selected' : '' }}>C</option>
                            </select>
                        </td>
                        <td><input type="number" name="idreceta" value="{{ $receta->idreceta }}"></td>
                        <td>{{ $receta->ficha }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Botón de guardar -->
            <button type="submit" class="btn btn-primary">Guardar</button>

            <br><br>

            <hr><hr>

            <br>

            <a href="{{ route('recetas.createDetalle', ['idReceta' => $receta->id, 'detalles' => implode(',', $recetasDetalles->pluck('id')->toArray())]) }}" class="btn btn-success">Añadir SKU</a>


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
                        <th>Acciones</th>
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
                            <td>
                                <a href="{{ route('recetas.editDetalle', ['detalle' => $detalle->id, 'idReceta' => $receta->id]) }}" class="btn btn-primary">Editar</a>
                                <div style="display: inline;">
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ route('recetas.destroyDetalle', ['detalle' => $detalle->id]) }}')">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br><br>

            <a href="{{ route('recetas.index') }}" class="btn btn-secondary">Volver</a>

        </form>

        <br><br>

    </div>

    <br><br>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        © COPYRIGHT 2023 CESANTONI. TODOS LOS DERECHOS RESERVADOS<br>
        Aviso de privacidad
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de validación del SKU -->
    <script>
        $(document).ready(function () {
            $('form').submit(function (event) {
                // Obtener el valor del SKU
                var skuValue = $('input[name="sku"]').val();

                // Verificar la longitud del SKU
                if (skuValue.length !== 13) {
                    // Mostrar una alerta si la longitud no es 13
                    alert('SKU debe tener 13 caracteres');
                    // Evitar que se envíe el formulario
                    event.preventDefault();
                }
                // Si la longitud es correcta, permitir que se envíe el formulario
            });
        });
    </script>

    <script>
        function confirmDelete(url) {
            if (confirm('¿Estás seguro de que deseas eliminar el material de la receta?')) {
                // Crear un formulario dinámico para enviar una solicitud DELETE
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';

                // Agregar el token CSRF
                var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                // Agregar el campo de método para simular DELETE
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                // Agregar los elementos al formulario
                form.appendChild(csrfInput);
                form.appendChild(methodInput);

                // Agregar el formulario al cuerpo del documento
                document.body.appendChild(form);

                // Enviar el formulario
                form.submit();
            }
        }
    </script>


</body>
</html>
