<?php
session_start();
include_once "../modelo/database.php";
include_once "header.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM datos_personales WHERE id = $id";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }

    $_SESSION['message'] = 'Empleado borrado con exito';
    $_SESSION['message_type'] = 'danger';

    mysqli_close($conn); 
    header("Location: index.php");
    exit(); 
}
?>
