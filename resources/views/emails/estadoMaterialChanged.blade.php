<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Estado de Material</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0066cc;
        }

        p {
            margin-bottom: 15px;
        }

        ul {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }

        li {
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cambio de Estado de Material</h2>

        <p>
            El material "{{ $data['descripcion_1'] }}" con código "{{ $data['codigoMpLimite'] }}" ha experimentado un cambio de estado.
        </p>

        <p>
            <strong>Estado Anterior:</strong> {{ $data['statusAnterior'] }}
        </p>
        <p>
            <strong>Estado Actual:</strong> {{ $data['status'] }}
        </p>

        <p>
            <strong>Detalles:</strong>
            <ul>
                <li>Límite Material: {{ number_format($data['limiteMaterial'], 2, '.', ',') }} KG</li>
                <li>Entregado: {{ number_format($data['nuevoEntregadoLimites'], 2, '.', ',') }} KG</li>
                <li>Porcentaje de Uso: {{ number_format($data['porcentajeUso'], 2, '.', ',') }}%</li>
            </ul>
        </p>

        <p id="graciasMensaje">
        </p>
    </div>

    <div class="footer">
        Este correo electrónico fue enviado automáticamente. Por favor, no responda a este mensaje.
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var currentDate = new Date();
            var formattedDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2);
            var formattedTime = ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2) + ':' + ('0' + currentDate.getSeconds()).slice(-2);

            var graciasElement = document.getElementById('graciasMensaje');
            graciasElement.innerHTML = 'Fecha y hora actual: ' + formattedDate + ' ' + formattedTime;
        });
    </script>
</body>
</html>
