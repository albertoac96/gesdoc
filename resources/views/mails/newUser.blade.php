<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Alta de usuario</title>
</head>
<body>
    <p>Hola! {{ $infoUser->name }}.</p>
    <p>Se te ha dado de alta el Sistema de Gestión de Documentos - INAH.</p>
    <p>Podras iniciar sesión con los siguientes datos.</p>
    <ul>
        <li>Correo: {{ $infoUser->email }}</li>
        <li>Contraseña: {{ $pass }}</li>
    </ul>
    <p>Una vez que inicies sesion puedes cambiar tu contraseña para mayor seguridad.</p>
    <p><a href="{{$url}}" target="_blank">Da click para ir al sistema</p>
</body>
</html>