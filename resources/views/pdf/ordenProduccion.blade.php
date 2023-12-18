<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orden de Producción</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            margin: 10px;
            font-size: 10px; /* Ajuste del tamaño de fuente general */
        }

        header, footer {
            text-align: center;
            background-color: #f2f2f2;
            padding: 5px;
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
            font-size: 22px;
        }

        p {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 9px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 9px; /* Ajuste del tamaño de fuente para tablas */
        }

        th {
            background-color: #f2f2f2;
        }

        .number {
            text-align: right;
        }

        .firmas {
            margin-top: 15px;
            text-align: center;
        }

        .firma {
            width: 45%;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 10px;
            position: relative;
            display: inline-block;
            font-size: 10px; /* Ajuste del tamaño de fuente para firmas */
        }

        .firma::before {
            content: "______________________";
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px; /* Ajuste del tamaño de fuente para la línea de firma */
            color: #555;
        }
    </style>
</head>
<body>

    <header>
        <h2>Orden de Producción - Número de Orden: {{ $numeroOrden }}</h2>
    </header>

    <p>Fecha: {{ $fechaActual }}</p>

    <strong>
        <p>Planta: {{ $planta }}</p> 
    </strong>

    <table>
        <thead>
            <tr>
                <th>Código MP</th>
                <th>Descripción</th>
                <th>Requerido</th>
                <th>UM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $registro)
                <tr>
                    <td>{{ $registro['codigo_mp'] }}</td>
                    <td>{{ $registro['descripcion_1'] }}</td>
                    <td class="number">{{ $registro['requerido'] }}</td>
                    <td>{{ $registro['um'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br><br><br>

    <div class="firmas">
        <div class="firma">
            <p>{{ $nombreUsuario }}</p>
        </div>
        <div class="firma">
            <p>Autoriza</p>
        </div>
    </div>

    <footer>
        <p>CESANTONI S.A. DE C.V.</p>
    </footer>
</body>
</html>
