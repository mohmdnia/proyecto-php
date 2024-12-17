<?php

    include_once "../modelo/database.php";
    include_once "header.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';
    $codigo_postal = isset($_POST['codigo_postal']) ? $_POST['codigo_postal'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
    $dni_nie = isset($_POST['dni_nie']) ? $_POST['dni_nie'] : '';
    $poblacion = isset($_POST['poblacion']) ? $_POST['poblacion'] : '';
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';

    $sql = "UPDATE...";

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $_SESSION['message'] = 'Empleado actualizado con exito';
    $_SESSION['message_type'] = 'success';
}
?>



<!-- codigo html -->
<div class="container mt-5">
        <h1>Editar Empleado</h1>

        <!-- Formulario de agregar empleado -->
        <form method="POST" action="editar.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellidos</label>
                <input type="text" class="form-control" name="apellido" id="apellido" required>
            </div>

            <div class="mb-3">
                <label for="codigo_postal" class="form-label">Codigo Postal</label>
                <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" required>
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" name="telefono" id="telefono" required>
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
            </div>

            <div class="mb-3">
                <label for="dni_nie" class="form-label">DNI/NIE</label>
                <input type="text" class="form-control" name="dni_nie" id="dni_nie" required>
            </div>

            <div class="mb-3">
                <label for="poblacion" class="form-label">Poblacion</label>
                <input type="text" class="form-control" name="poblacion" id="poblacion" required>
            </div>

            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-control" name="sexo" id="sexo" required>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Editar Empleado</button>
            
            <div class="d-inline-flex gap-1">
                <a href="index.php" class="btn" role="button" data-bs-toggle="button"> Volver</a>
            </div>
        </form>
    </div>
