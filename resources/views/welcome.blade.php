<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .button-container { display: flex; gap: 10px; }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Bienvenido</h1>
    <div class="button-container">
        <a href="{{ route('personas') }}"><button>Ir a Personas</button></a>
        <a href="{{ route('viviendas') }}"><button>Ir a Propiedades</button></a>
        <a href="{{ route('solicitud') }}"><button>Ir a Solicitud de Visita</button></a>
    </div>
</body>
</html>