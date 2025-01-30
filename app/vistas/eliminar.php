<?php
session_start();
include_once "../modelo/database.php";
include_once "header.php";

// Verificamos si se pasó el parámetro 'dni' en la URL
if (isset($_GET['dni'])) {
    $dni = $_GET['dni']; 

    // Primero eliminamos de las tablas relacionadas, si es necesario
    $query = $conn->prepare("DELETE FROM 340_personal_epsevg WHERE dni = ?");
    $query->bind_param("s", $dni);
    $query->execute();
    $query->close();

    // Luego eliminamos al empleado de la tabla principal
    $query = $conn->prepare("DELETE FROM 340_personal WHERE dni = ?");
    $query->bind_param("s", $dni);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            $_SESSION['message'] = 'Empleado borrado con éxito';
            $_SESSION['message_type'] = 'danger';
        } else {
            $_SESSION['message'] = 'No se encontró el empleado con el DNI proporcionado';
            $_SESSION['message_type'] = 'warning';
        }
        $query->close(); 
    } else {
        $_SESSION['message'] = 'Error al eliminar el empleado';
        $_SESSION['message_type'] = 'warning';
    }
} else {
    $_SESSION['message'] = 'No se proporcionó ningún DNI';
    $_SESSION['message_type'] = 'warning';
}

// Redirigir al índice 
header("Location: index.php");
exit();
?>
