<?php
include_once "../modelo/database.php";
include_once "header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualización de los datos personales
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $cognoms = isset($_POST['cognoms']) ? $_POST['cognoms'] : '';
    $cp = isset($_POST['cp']) ? $_POST['cp'] : '';
    $telefon = isset($_POST['telefon']) ? $_POST['telefon'] : '';
    $telf_movil = isset($_POST['telf_movil']) ? $_POST['telf_movil'] : '';
    $data_naixement = isset($_POST['data_naixement']) ? $_POST['data_naixement'] : '';
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    $poblacio = isset($_POST['poblacio']) ? $_POST['poblacio'] : '';
    $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';


    // Consultar SQL para actualizar los datos personales
    $sql = "UPDATE 340_personal SET 
            nom = '$nom',
            cognoms = '$cognoms',
            cp = '$cp',
            telefon = '$telefon',
            telf_movil = '$telf_movil',
            data_naixement = '$data_naixement',
            dni = '$dni',
            poblacio = '$poblacio',
            sexe = '$sexe'
            WHERE dni = '$dni'";

    if (mysqli_query($conn, $sql)) {
        // Redirección con mensaje de éxito
        $_SESSION['message'] = 'Datos personales actualizados con éxito.';
        $_SESSION['message_type'] = 'success';
        header('Location: index.php');
        exit;
    } else {
        // Error de actualización
        error_log("Error al ejecutar la consulta SQL: " . mysqli_error($conn));
        $_SESSION['message'] = 'Error al actualizar los datos. Por favor, inténtalo de nuevo.';
        $_SESSION['message_type'] = 'danger';
        header('Location: index.php');
        exit;
    }

    // Actualización de los datos EPSEVG
    $cip = isset($_POST['cip']) ? $_POST['cip'] : '';
    $telf1 = isset($_POST['telf1']) ? $_POST['telf1'] : '';
    $numero_expedient = isset($_POST['numero_expedient']) ? $_POST['numero_expedient'] : '';
    $incid = isset($_POST['incid']) ? $_POST['incid'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $dedicacio = isset($_POST['dedicacio']) ? $_POST['dedicacio'] : '';
    $departament = isset($_POST['departament']) ? $_POST['departament'] : '';
    $tasca = isset($_POST['tasca']) ? $_POST['tasca'] : '';
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telf2 = isset($_POST['telf2']) ? $_POST['telf2'] : '';
    $unitat_estructural = isset($_POST['unitat_estructural']) ? $_POST['unitat_estructural'] : '';
    $tipus_asociat = isset($_POST['tipus_asociat']) ? $_POST['tipus_asociat'] : '';
    $titulacio = isset($_POST['titulacio']) ? $_POST['titulacio'] : '';
    $despatx = isset($_POST['despatx']) ? $_POST['despatx'] : '';
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';


    // Actualización de los datos en la tabla EPSEVG
    $sql = "UPDATE 340_personal_epsevg SET 
                cip = '$cip', 
                telf1 = '$telf1', 
                numero_expedient = '$numero_expedient',
                incid = '$incid', 
                categoria = '$categoria', 
                dedicacio = '$dedicacio', 
                departament = '$departament', 
                tasca = '$tasca', 
                dni = '$dni',
                unitat_estructural = '$unitat_estructural', 
                tipus_asociat = '$tipus_asociat', 
                titulacio = '$titulacio', 
                despatx = '$despatx', 
                perfil = '$perfil'
            WHERE dni = '$dni'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Datos de EPSEVG actualizados con éxito.';
        $_SESSION['message_type'] = 'success';
        header('Location: index.php');
        exit;
    } else {
        error_log("Error al ejecutar la consulta SQL: " . mysqli_error($conn));
        $_SESSION['message'] = 'Error al actualizar los datos de EPSEVG. Por favor, inténtalo de nuevo.';
        $_SESSION['message_type'] = 'danger';
        header('Location: index.php');
        exit;
    }
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
