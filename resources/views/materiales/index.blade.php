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

    <title>Materiales - Index</title>
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

        <h2>Listado de Materiales</h2>

        <!-- Botón Agregar Material -->
	    <div class="mb-3">
	        <a href="{{ route('materiales.create') }}" class="btn btn-success">Agregar Material</a>
	    </div>

        <form id="uploadForm" action="{{ route('uploadMateriales') }}" method="POST" enctype="multipart/form-data" class="form-row align-items-center">
            @csrf
            <div class="col-6">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="excel_file" name="excel_file" accept=".xlsx, .xls" onchange="updateFileName(this)">
                    <label class="custom-file-label" for="excel_file">Elegir Archivo Excel</label>
                </div>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary w-100" id="uploadButton">Actualizar Materiales</button>
            </div>
        </form>

<script>
    function updateFileName(input) {
        var fileName = input.files[0].name;
        var label = input.nextElementSibling;
        label.innerText = fileName;
    }
</script>

        <a href="{{ route('welcome') }}" class="btn btn-secondary" style="margin-top: 12px;">Volver</a>

        <br><br>

        <!-- Tabla con las materiales -->
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CODIGO MP</th>
                    <th>DESCRIPCION 1</th>
                    <!-- Agrega las demás columnas según los campos -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materiales as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->codigo_mp }}</td>
                        <td>{{ $material->descripcion_1 }}</td>
                        <!-- Agrega las demás columnas según los campos -->
                        <td>
                            <!-- Enlaces a las acciones como editar, mostrar y eliminar -->
                            <a href="{{ route('materiales.edit', $material->id) }}" class="btn btn-primary">Editar</a>
                            <form action="{{ route('materiales.destroy', $material->id) }}" method="POST" style="display: inline;">
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
     <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add an event listener to the form submit button
            document.getElementById('uploadButton').addEventListener('click', function (event) {
                // Check if a file has been selected
                var fileInput = document.getElementById('excel_file');
                if (!fileInput.files.length) {
                    // Prevent form submission
                    event.preventDefault();
                    // Display an alert
                    alert('Please select a file before uploading.');
                }
            });
        });
    </script>

</body>
</html>
