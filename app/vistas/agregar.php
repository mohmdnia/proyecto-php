<?php

// Incluir conexión base de datos 
include_once "../modelo/database.php";
include_once "header.php";

// verificar envio formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';
    $codigo_postal = isset($_POST['codigo_postal']) ? $_POST['codigo_postal'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
    $dni_nie = isset($_POST['dni_nie']) ? $_POST['dni_nie'] : '';
    $poblacion = isset($_POST['poblacion']) ? $_POST['poblacion'] : '';
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';

    // Insertar los datos
    $sql = "INSERT INTO 340_personal (nombre, apellido, codigo_postal, telefono, fecha_nacimiento, dni_nie, poblacion, sexo)
            VALUES ('$nombre', '$apellido', '$codigo_postal', '$telefono', '$fecha_nacimiento', '$dni_nie', '$poblacion', '$sexo')";


    if (mysqli_query($conn, $sql)) {
        // Mensaje de exito
        $_SESSION['message'] = 'Datos personales agregados con éxito.';
        $_SESSION['message_type'] = 'success';

        // Redirección al index
        header('Location: agregar.php');
        exit;

    } else {
        // Mensaje de error
        error_log("Error al ejecutar la consulta SQL: " . mysqli_error($conn));

        $_SESSION['message'] = 'Ocurrió un error al agregar los datos. Por favor, inténtalo de nuevo.';
        $_SESSION['message_type'] = 'danger';

        header('Location: agregar.php');
        exit;
}
}


// Verificar envio formulario epsevg
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $cip = isset($_POST['cip']) ? $_POST['cip'] : '';
//     $telefono_1 = isset($_POST['telefono_1']) ? $_POST['telefono_1'] : '';
//     $num_expediente = isset($_POST['num_expediente']) ? $_POST['num_expediente'] : '';
//     $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
//     $dedicacion = isset($_POST['dedicacion']) ? $_POST['dedicacion'] : '';
//     $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : '';
//     $tarea = isset($_POST['tarea']) ? $_POST['tarea'] : '';
//     $email = isset($_POST['email']) ? $_POST['email'] : '';
//     $telefono_2 = isset($_POST['telefono_2']) ? $_POST['telefono_2'] : '';
//     $unidad_estructural = isset($_POST['unidad_estructural']) ? $_POST['unidad_estructural'] : '';
//     $tipo_asociado = isset($_POST['tipo_asociado']) ? $_POST['tipo_asociado'] : '';
//     $titulacion = isset($_POST['titulacion']) ? $_POST['titulacion'] : '';
//     $despacho = isset($_POST['despacho']) ? $_POST['despacho'] : '';
//     $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';

//     // Insertar los datos en la base de datos
//     $sql = "INSERT INTO 340_personales_epsevg (cip, telefono_1, num_expediente, categoria, dedicacion, departamento, tarea, email,
//                 telefono_2, unidad_estructural, tipo_asociado, titulacion, despacho, perfil)
//             VALUES ('$cip', '$telefono_1', '$num_expediente', '$categoria', '$dedicacion', '$departamento', '$tarea', '$email', '$telefono_2', 
//                     '$unidad_estructural', '$tipo_asociado', '$titulacion', '$despacho', '$perfil')";

//     if (mysqli_query($conn, $sql)) {
//         $_SESSION['message'] = 'Datos EPSEVG agregados con exito';
//         $_SESSION['message_type'] = 'success';
//         header('Location: index.php');
//         exit;
//     } else {
//         echo "Error: " . mysqli_error($conn);
//     }
// }

?>

<!-- Código HTML -->

<!-- Contenedor con los dos formularios uno al lado del otro -->
<div class="container mt-5">
    <div class="row">
        
        <!-- Formulario de agregar datos personales empleado -->
        <div class="col-md-6 mb-4">
            <div class="card bg-light p-3">
                <h4>Agregar Empleado</h3>
                <form method="POST" action="agregar.php">
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

                    <button type="submit" class="btn btn-primary">Agregar Empleado</button>
                </form>
            </div>
        </div>

        <!-- Formulario de agregar datos EPSEVG -->
        <div class="col-md-6 mb-4">
            <div class="card bg-light p-3">
                <h4>Agregar Datos EPSEVG</h3>
                <form method="POST" action="agregar.php">
                    <div class="mb-3">
                        <label for="cip" class="form-label">CIP</label>
                        <input type="text" class="form-control" name="cip" id="cip" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono_1" class="form-label">Telefono 1</label>
                        <input type="text" class="form-control" name="telefono_1" id="telefono_1" required>
                    </div>

                    <div class="mb-3">
                        <label for="num_expediente" class="form-label">Número de expediente</label>
                        <input type="text" class="form-control" name="num_expediente" id="num_expediente" required>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" class="form-control" name="categoria" id="categoria" required>
                    </div>

                    <div class="mb-3">
                        <label for="dedicacion" class="form-label">Dedicación</label>
                        <input type="text" class="form-control" name="dedicacion" id="dedicacion" required>
                    </div>

                    <div class="mb-3">
                        <label for="departamento" class="form-label">Departamento</label>
                        <input type="text" class="form-control" name="departamento" id="departamento" required>
                    </div>

                    <div class="mb-3">
                        <label for="tarea" class="form-label">Tarea</label>
                        <input type="text" class="form-control" name="tarea" id="tarea" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono_2" class="form-label">Teléfono 2</label>
                        <input type="text" class="form-control" name="telefono_2" id="telefono_2" required>
                    </div>

                    <div class="mb-3">
                        <label for="unidad_estructural" class="form-label">Unidad estructural</label>
                        <input type="text" class="form-control" name="unidad_estructural" id="unidad_estructural" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_asociado" class="form-label">Tipo asociado</label>
                        <input type="text" class="form-control" name="tipo_asociado" id="tipo_asociado" required>
                    </div>

                    <div class="mb-3">
                        <label for="titulacion" class="form-label">Titulación</label>
                        <input type="text" class="form-control" name="titulacion" id="titulacion" required>
                    </div>

                    <div class="mb-3">
                        <label for="despacho" class="form-label">Despacho</label>
                        <input type="text" class="form-control" name="despacho" id="despacho" required>
                    </div>

                    <div class="mb-3">
                        <label for="perfil" class="form-label">Perfil</label>
                        <input type="text" class="form-control" name="perfil" id="perfil" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Agregar Datos EPSEVG</button>

                    <!-- Boton para volver al indice -->
                    <div class="d-inline-flex gap-1">
                            <a href="index.php" class="btn" role="button" data-bs-toggle="button"> Volver</a>
                    </div>

                </form>
            </div>
        </div>


    </div>
</div>
