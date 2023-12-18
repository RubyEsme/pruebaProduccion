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

    <style>
        
        .btnhistoricoCotejo{
            margin-left: 15px;
            margin-right: 15px;
            height: 42px;
        }

        .btnVolver{
            margin-right: 235px;
            height: 42px;
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

    <!-- Contenido principal -->
    <div class="container mt-3">

        <?php
            // Inicializa variables para los totales
            $totalLimite = 0;
            $totalEntregado = 0;
            $totalPorcentajeUso = 0;
        ?>

        @foreach($limites as $limite)

            <?php
                $totalLimite += $limite->limite;
                $totalEntregado += $limite->entregado;
                $totalPorcentajeUso = ($totalEntregado / $totalLimite) * 100;
            ?>

        @endforeach

        <br>

        <h2>Listado de Limite VS Cotejo</h2>

        <br>

        <div class="row mb-3">

            <a href="{{ route('limites.historicoCotejo') }}" class="btn btn-info btnhistoricoCotejo">Ver histórico Cotejo Vs Límite</a>

            <a href="{{ route('welcome') }}" class="btn btn-secondary btnVolver">Volver</a>

            <div class="col-md-2">
                <label>Total Límite:</label>
                <div class="input-group">
                    <input type="text" class="form-control font-weight-bold text-success" value="{{ number_format($totalLimite, 2, '.', ',') }}" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text bg-success text-light">KG</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label>Total Entregado:</label>
                <div class="input-group">
                    <input type="text" class="form-control font-weight-bold text-primary" value="{{ number_format($totalEntregado, 2, '.', ',') }}" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text bg-primary text-light">KG</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label>Total % Uso:</label>
                <div class="input-group">
                    <input type="text" class="form-control font-weight-bold text-warning" value="{{ number_format($totalPorcentajeUso, 2, '.', ',') }}" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text bg-warning text-light">%</span>
                    </div>
                </div>
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
                    </tr>
                @endforeach
            </tbody>
        </table>

        <style>
            .totals-row {
                position: sticky;
                top: 0;
                background-color: white;
                z-index: 2;
            }
        </style>

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
