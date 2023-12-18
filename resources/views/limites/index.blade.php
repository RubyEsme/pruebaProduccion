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

    <title>Limites - Index</title>
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

        <h2>Listado de Limites</h2>

        @if($limites->count() > 0)
            <!-- Botón Agregar Limite -->
            <div class="mb-3">
                <a href="{{ route('limites.create') }}" class="btn btn-success">Agregar Limite</a>
            </div>
        @endif

        <!-- Botón Corte de mes -->
        @if($limites->count() > 0)
            <button class="btn btn-warning" data-toggle="modal" data-target="#confirmarCorteModal">Corte de mes 
                @php
                    // Obtén el valor del mes del primer registro
                    $primerRegistro = $limites->first();
                    if ($primerRegistro) {
                        $mesPrimerRegistro = $primerRegistro->mes;
                        switch ($mesPrimerRegistro) {
                            case 1:
                                echo 'Enero';
                                break;
                            case 2:
                                echo 'Febrero';
                                break;
                            case 3:
                                echo 'Marzo';
                                break;
                            case 4:
                                echo 'Abril';
                                break;
                            case 5:
                                echo 'Mayo';
                                break;
                            case 6:
                                echo 'Junio';
                                break;
                            case 7:
                                echo 'Julio';
                                break;
                            case 8:
                                echo 'Agosto';
                                break;
                            case 9:
                                echo 'Septiembre';
                                break;
                            case 10:
                                echo 'Octubre';
                                break;
                            case 11:
                                echo 'Noviembre';
                                break;
                            case 12:
                                echo 'Diciembre';
                                break;
                            default:
                                echo 'Mes Desconocido';
                        }
                    }
                @endphp
            </button>
        @endif

        <a href="{{ route('limites.historico') }}" class="btn btn-info">Ver histórico Límites</a>
        
        <form id="uploadForm" action="{{ route('uploadLimites') }}" method="POST" enctype="multipart/form-data" class="form-row align-items-center" style="margin-top: 25px;">
            @csrf
            <div class="col-6">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="excel_file" name="excel_file" accept=".xlsx, .xls" onchange="updateFileName(this)">
                    <label class="custom-file-label" for="excel_file">Elegir Archivo Excel</label>
                </div>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary w-100" id="uploadButton">Actualizar Limites</button>
            </div>
        </form>

<script>
    function updateFileName(input) {
        var fileName = input.files[0].name;
        var label = input.nextElementSibling;
        label.innerText = fileName;
    }
</script>

        <a href="{{ route('welcome') }}" class="btn btn-secondary" style="margin-top: 15px;">Volver</a>

        <br><br>

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
                    <th>OBSERVACIONES</th>
                    <!-- Agrega las demás columnas según los campos -->
                    <th>Acciones</th>
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
                        <td>{{ $limite->observciones }}</td>
                        <!-- Agrega las demás columnas según los campos -->
                        <td>
                            <!-- Enlaces a las acciones como editar, mostrar y eliminar -->
                            <a href="{{ route('limites.edit', $limite->id) }}" class="btn btn-primary">Editar</a>
                            <form action="{{ route('limites.destroy', $limite->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar el material?')">Eliminar</button>
                            </form>
                        </td>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (después de jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        new DataTable('#example');
    </script>

    <!-- Modal para confirmar corte de mes -->
    <div class="modal fade" id="confirmarCorteModal" tabindex="-1" role="dialog" aria-labelledby="confirmarCorteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarCorteModalLabel">Confirmar Corte de Mes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Por favor, ingrese su contraseña para confirmar el corte de mes:</p>
                    <input type="password" id="passwordInput" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="confirmarCorteMes()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmarCorteMes() {
            // Obtiene la contraseña ingresada por el usuario
            var password = document.getElementById('passwordInput').value;

            // Obtiene el token CSRF de Laravel
            var csrfToken = "{{ csrf_token() }}";

            // Realiza la verificación de la contraseña utilizando una petición AJAX
            $.ajax({
                url: "{{ route('limites.verificarPassword') }}",
                type: "POST",
                data: {
                    _token: csrfToken,
                    password: password,
                },
                success: function(response) {
                    if (response.success) {
                        // Contraseña correcta, realiza el corte de mes
                        window.location.href = "{{ route('limites.corteMes') }}";
                    } else {
                        // Contraseña incorrecta, muestra un mensaje de error
                        alert('Contraseña incorrecta. Intenta de nuevo.');
                    }
                },
                error: function(error) {
                    console.error('Error al verificar la contraseña:', error);
                    alert('Ocurrió un error al verificar la contraseña. Por favor, inténtalo de nuevo.');
                }
            });
        }
    </script>

</body>
</html>
