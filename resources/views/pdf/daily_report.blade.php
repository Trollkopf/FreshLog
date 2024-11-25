<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Diario de Registros de Limpieza</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 8px;
        }

        h3 {
            text-align: center;
            margin-top: 8px;
        }

        img {
            align-content: center;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <img src="file://{{ public_path('images/logo.png') }}" alt="logo" width="100px" />
    <h2>Informe Diario de Registros de Limpieza</h2>
    <h3>{{ config('app.location') }}</h3>
    <p>Fecha: {{ \Carbon\Carbon::today()->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Nombre del Operario</th>
                <th>Fecha de Limpieza</th>
                <th>Hora de Limpieza</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cleanings as $cleaning)
                <tr>
                    <td>{{ $cleaning->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($cleaning->cleaned_at)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cleaning->cleaned_at)->format('H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
