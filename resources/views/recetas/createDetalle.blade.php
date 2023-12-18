<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-success{
            margin-top: 30px;
            width: 160px;
        }

    </style>

    <title>Crear Detalle Receta</title>
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

        <h2>Añadir Material a la Receta</h2>

        <br>

        <!-- Formulario de edición -->
        <form id="createForm" action="{{ route('recetas.storeDetalle', ['idReceta' => $idReceta]) }}" method="POST" onsubmit="return validarFormulario()">
        @csrf
        @method('POST')

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" class="form-control" value="{{ $receta->modelo }}" readonly>
                    </div>
                </div>    

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formato">Formato:</label>
                        <input type="text" class="form-control" value="{{ $receta->formato }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="planta">Planta:</label>
                        <input type="number" class="form-control" value="{{ $receta->planta }}" readonly>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="descripcion_1">Descripción 1:</label>
                        <select id="descripcion_1" name="descripcion_1" class="form-control" onchange="mostrarCodigoMP()">
                            <option value="" selected disabled>Seleccione una descripción</option>
                            @foreach($materiales->sortBy('descripcion_1') as $material)
                                <option value="{{ $material->descripcion_1 }}" data-codigo-mp="{{ $material->codigo_mp }}">{{ $material->descripcion_1 }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codigo_mp">Código MP:</label>
                        <input type="text" id="codigo_mp" name="codigo_mp" class="form-control" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <select id="descripcion" name="descripcion" class="form-control">
                            <option value="" selected disabled>Seleccione una descripción</option>
                            @foreach(['BASE', 'COLOR', 'CUBIERTA', 'ENGOBE', 'SERIGRAFIA', 'TINTA', 'VEHICULO'] as $opcion)
                                <option value="{{ $opcion }}">{{ $opcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cantidad">Cantidad (GR):</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" min="0.0001" step="any">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="rodillo_digital">Rodillo / Digital:</label>
                        <select id="rodillo_digital" name="rodillo_digital" class="form-control">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="Rodillo">Rodillo</option>
                            <option value="Digital">Digital</option>
                            <option value="Rodillo / Digital">Rodillo / Digital</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success top">Añadir</button>
                    </div>
                </div>

            </div>

            <br>

            <a href="{{ route('recetas.edit', $idReceta) }}" class="btn btn-secondary">Volver</a>
        </form>

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
        function mostrarCodigoMP() {
            var select = document.getElementById("descripcion_1");
            var codigoMPInput = document.getElementById("codigo_mp");
            var selectedOption = select.options[select.selectedIndex];

            // Obtener el valor del atributo data-codigo-mp de la opción seleccionada
            var codigoMP = selectedOption.getAttribute("data-codigo-mp");

            // Mostrar el código MP en el input correspondiente
            codigoMPInput.value = codigoMP;
        }
    </script>

    <!-- Agrega la función de validación -->
    <script>
        function validarFormulario() {
            // Obtener los valores de los campos
            var descripcion_1 = document.getElementById("descripcion_1").value;
            var codigo_mp = document.getElementById("codigo_mp").value;
            var descripcion = document.getElementById("descripcion").value;
            var cantidad = document.getElementById("cantidad").value;
            var rodillo_digital = document.getElementById("rodillo_digital").value;

            // Verificar que todos los campos estén llenos
            if (!descripcion_1 || !codigo_mp || !descripcion || !cantidad || !rodillo_digital) {
                alert("Por favor, complete todos los campos.");
                return false; // Detener el envío del formulario
            }

            // Verificar si el codigo_mp ya existe en detallesReceta
            var codigoMps = {!! json_encode($codigoMps) !!};
            if (codigoMps.includes(codigo_mp)) {
                alert("El código MP ya existe en los detalles de la receta.");
                return false; // Detener el envío del formulario
            }

            // Si todas las validaciones son exitosas, permitir el envío del formulario
            return true;
        }
    </script>

</body>
</html>
