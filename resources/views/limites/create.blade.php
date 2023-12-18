<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Crear Limite</title>
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

        <h2>Nuevo Limite</h2>

        <br>

        <!-- Formulario de creación -->
        <form action="{{ route('limites.store') }}" method="POST" onsubmit="return validarFormulario()">
            @csrf

            <div class="form-group">
                <label for="descripcion_1">Descripción 1:</label>
                <select id="descripcion_1" name="descripcion_1" class="form-control" onchange="mostrarCodigoMP()" required>
                    <option value="" selected disabled>Seleccione una descripción</option>
                    @foreach($materiales->sortBy('descripcion_1') as $material)
                        <option value="{{ $material->descripcion_1 }}" data-codigo-mp="{{ $material->codigo_mp }}">{{ $material->descripcion_1 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="codigo_mp">Código MP:</label>
                <input type="text" id="codigo_mp" name="codigo_mp" class="form-control" readonly required>
            </div>

            <div class="form-group">
                <label for="mes">Mes:</label>
                <input type="text" class="form-control" value="
                    @php
                        $mesActual = date('n');
                        switch ($mesActual) {
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
                    @endphp
                " readonly required>
                <input type="hidden" id="mes_hidden" name="mes" value="{{ $mesActual }}">
            </div>

            <div class="form-group">
                <label for="año">Año:</label>
                <input type="number" class="form-control" id="año" name="año" value="{{ date('Y') }}" readonly required>
            </div>

            <div class="form-group">
                <label for="limite">Limite (KG):</label>
                <input type="number" class="form-control" id="limite" name="limite" min="1" required>
            </div>

            <br>

            <button type="submit" class="btn btn-success">Guardar</button>

            <!-- Botón de volver al índice -->
            <a href="{{ route('limites.index') }}" class="btn btn-secondary">Volver</a>

            <br><br>
            
        </form>

        <br>
    </div>

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

        function validarFormulario() {
            // Verificar campos vacíos
            var descripcion1 = document.getElementById("descripcion_1").value;
            var codigoMP = document.getElementById("codigo_mp").value;
            var mes = document.getElementById("mes").value;
            var año = document.getElementById("año").value;
            var limite = document.getElementById("limite").value;

            if (!descripcion1 || !codigoMP || !mes || !año || !limite) {
                alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
                return false; // Detener el envío del formulario
            }

            // Verificar si el codigo_mp ya existe en detallesReceta
            var codigoMps = {!! json_encode($codigoMps) !!};
            if (codigoMps.includes(codigoMP)) {
                alert("El código MP ya existe en los detalles de la receta.");
                return false; // Detener el envío del formulario
            }

            // Si todas las validaciones son exitosas, permitir el envío del formulario
            return true;
        }
    </script>

</body>
</html>
