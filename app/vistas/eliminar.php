<?php
session_start();
include_once "../modelo/database.php";
include_once "header.php";

// Verificamos si se pasó el parámetro 'dni' en la URL
if (isset($_GET['dni'])) {
    $dni = $_GET['dni']; 

    // Preparamos la consulta para marcar al usuario como eliminado
    $query = $conn->prepare("UPDATE 340_personal SET is_deleted = 1 WHERE dni = ?");
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
        $conn->close();      

    } else {
        $_SESSION['message'] = 'Error al eliminar el empleado';
        $_SESSION['message_type'] = 'warning';
    }

} else {
    // Si no se proporciona un DNI en la URL
    $_SESSION['message'] = 'No se proporcionó ningún DNI';
    $_SESSION['message_type'] = 'warning';
}

// Redirigir al índice (o a la página que deseas después de la eliminación)
header("Location: index.php");
exit();
?>
