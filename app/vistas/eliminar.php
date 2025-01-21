<?php
session_start();
include_once "../modelo/database.php";
include_once "header.php";

// Verificamos si se pasó el parámetro 'id' en la URL
if (isset($_GET['dni'])) {
    $dni = $_GET['dni']; 

    $query = $conn->prepare("DELETE FROM 340_personal WHERE dni = ?");
    $query->bind_param("s", $dni);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            $_SESSION['message'] = 'Empleado borrado con éxito';
            $_SESSION['message_type'] = 'danger';
        } else {
            $_SESSION['message'] = 'No se encontró el empleado con el ID proporcionado';
            $_SESSION['message_type'] = 'warning';
        }
        
        $query->close(); 
        $conn->close();      

    } else {
        $_SESSION['message'] = 'Error al eliminar el empleado';
        $_SESSION['message_type'] = 'warning';
    }

} else {
    // Si no se proporciona un ID en la URL
    $_SESSION['message'] = 'No se proporcionó ningún ID';
    $_SESSION['message_type'] = 'warning';
}

// Redirigir al índice (o a la página que deseas después de la eliminación)
header("Location: index.php");
exit();
?>
