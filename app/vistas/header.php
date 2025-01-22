<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../modelo/database.php";
$usuario = "Invitado";

if (isset($_SESSION['usuario'])) {
    $usuario = htmlspecialchars($_SESSION['usuario']);
}

// Comprobar si hay un mensaje de sesión
if (isset($_SESSION['message'])) {
    // Mostrar el mensaje de la sesión
    echo '<div class="alert alert-' . $_SESSION['message_type'] . '">';
    echo $_SESSION['message'];
    echo '</div>';

    // Limpiar el mensaje de sesión después de mostrarlo
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrapp y css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/proyecto-empleados/assets/css/style.css">
    <title>Portal Empleados</title>
</head>
<body>
    
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <img src="/proyecto-empleados/assets/img/logo-epsevg.png" alt="logo epsevg" style="width:200px;">
            <a href="index.php" class="navbar-brand">Portal Empleados UPC - EPSEVG</a>
            <h3 class="navbar-brand">Hola, <?= $usuario ?></h3>
        </div>
    </nav>
</body>
</html>