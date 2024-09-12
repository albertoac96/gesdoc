<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Alta de usuario</title>
</head>
<body>
    <p>Hola, has dado de alta un usuario en el el Sistema de Gestión de Documentos - INAH.</p>
    <p>Los datos del usuario son los siguientes:</p>
    <ul>
        <li>Nombre: {{ $infoUser->name }}</li>
        <li>Correo: {{ $infoUser->email }}</li>
        <li>Contraseña: {{ $pass }}</li>
    </ul>
    <p>El usuario puede cambiar esta contraseña desde su perfil.</p>
    <p><a href="{{$url}}" target="_blank">Da click para ir al sistema</p>
</body>
</html>