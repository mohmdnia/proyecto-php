<?php

// Incluir conexión base de datos 
include_once "../modelo/database.php";
include_once "header.php";

// verificar envio formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $cognoms = isset($_POST['cognoms']) ? $_POST['cognoms'] : '';
    $cp = isset($_POST['cp']) ? $_POST['cp'] : '';
    $telefon = isset($_POST['telefon']) ? $_POST['telefon'] : '';
    $telf_movil = isset($_POST['telf_movil']) ? $_POST['telf_movil'] : '';
    $data_naixement = isset($_POST['data_naixement']) ? $_POST['data_naixement'] : '';
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    $poblacio = isset($_POST['poblacio']) ? $_POST['poblacio'] : '';
    $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';

    // Insertar los datos
            $sql = "INSERT INTO 340_personal (nom, cognoms, cp, telefon, telf_movil, data_naixement, dni, poblacio, sexe)
            VALUES ('$nom', '$cognoms', '$cp', '$telefon', '$telf_movil', '$data_naixement', '$dni', '$poblacio', '$sexe')";


    if (mysqli_query($conn, $sql)) {
        // Mensaje de exito
        $_SESSION['message'] = 'Dades personals afegides amb éxit.';
        $_SESSION['message_type'] = 'success';

        // Redirección al index
        header('Location: agregar.php');
        exit;

    } else {
        // Mensaje de error
        error_log("Error al executar la consulta SQL: " . mysqli_error($conn));

        $_SESSION['message'] = 'Sha produït un error en afegir les dades. Si us plau, torna-ho a intentar.';
        $_SESSION['message_type'] = 'danger';

        header('Location: agregar.php');
        exit;
}
}


// Verificar envio formulario epsevg
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO 340_personal_epsevg (cip, telf1, numero_expedient, incid, categoria, dedicacio, departament, tasca,
                dni, unitat_estructural, tipus_asociat, titulacio, despatx, perfil)
            VALUES ('$cip', '$telf1', '$numero_expedient', '$incid', '$categoria', '$dedicacio', '$departament', '$tasca', '$dni', 
                    '$unitat_estructural', '$tipus_asociat', '$titulacio', '$despatx', '$perfil')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Datos EPSEVG agregados con exito';
        $_SESSION['message_type'] = 'success';
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!-- Código HTML -->

<!-- Contenedor con los dos formularios uno al lado del otro -->
<div class="container mt-5">
    <div class="row">
        
        <!-- Formulario de agregar datos personales empleado -->
        <div class="col-md-6 mb-4">
            <div class="card bg-light p-3">
                <h4>Afegir Empleat</h3>
                <form method="POST" action="agregar.php">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" id="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="cognoms" class="form-label">Cognoms</label>
                        <input type="text" class="form-control" name="cognoms" id="cognoms" required>
                    </div>

                    <div class="mb-3">
                        <label for="sexo" class="form-label">Sexe</label>
                        <select class="form-control" name="sexo" id="sexo" required>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="poblacion" class="form-label">Domicili</label>
                        <input type="text" class="form-control" name="poblacion" id="poblacion" required>
                    </div>

                    <div class="mb-3">
                        <label for="cp" class="form-label">Codi Postal</label>
                        <input type="text" class="form-control" name="cp" id="cp" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefon" class="form-label">Telefon</label>
                        <input type="text" class="form-control" name="telefon" id="telefon" required>
                    </div>

                    <div class="mb-3">
                        <label for="telf_movil" class="form-label">Telefon Movil</label>
                        <input type="text" class="form-control" name="telf_movil" id="telf_movil" required>
                    </div>


                    <div class="mb-3">
                        <label for="data_naixement" class="form-label">Data de Naixement</label>
                        <input type="date" class="form-control" name="data_naixement" id="data_naixement" required>
                    </div>

                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI/NIE</label>
                        <input type="text" class="form-control" name="dni" id="dni" required>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Afegir Empleat</button>
                </form>
            </div>
        </div>

        <!-- Formulario de agregar datos EPSEVG -->
        <div class="col-md-6 mb-4">
            <div class="card bg-light p-3">
                <h4>Afegir Dades EPSEVG</h3>
                
                <form method="POST" action="agregar.php">
                    <div class="mb-3">
                        <label for="cip" class="form-label">CIP</label>
                        <input type="text" class="form-control" name="cip" id="cip" required>
                    </div>

                    <div class="mb-3">
                        <label for="telf1" class="form-label">Telefon 1</label>
                        <input type="text" class="form-control" name="telf1" id="telf1" required>
                    </div>

                    <div class="mb-3">
                        <label for="telf2" class="form-label">Telefon 2</label>
                        <input type="text" class="form-control" name="telf2" id="telf2" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="numero_expedient" class="form-label">Número de expedient</label>
                        <input type="text" class="form-control" name="numero_expedient" id="numero_expedient" required>
                    </div>

                    <div class="mb-3">
                        <label for="unitat_estructural" class="form-label">Unitat Estructural</label>
                        <input type="text" class="form-control" name="categoria" id="categoria" required>
                    </div>

                    <div class="mb-3">
                        <label for="inicid" class="form-label">Inicid</label>
                        <input type="text" class="form-control" name="inicid" id="inicid" required>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <input type="text" class="form-control" name="categoria" id="categoria" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipus_associat" class="form-label">tipus_associat</label>
                        <input type="text" class="form-control" name="tipus_associat" id="tipus_associat" required>
                    </div>

                    <div class="mb-3">
                        <label for="dedicacio" class="form-label">Dedicació</label>
                        <input type="text" class="form-control" name="dedicacio" id="dedicacio" required>
                    </div>

                    <div class="mb-3">
                        <label for="titulacio" class="form-label">Titulació</label>
                        <input type="text" class="form-control" name="titulacio" id="titulacio" required>
                    </div>

                    <div class="mb-3">
                        <label for="departament" class="form-label">Departament</label>
                        <input type="text" class="form-control" name="departament" id="departament" required>
                    </div>

                    <div class="mb-3">
                        <label for="tasca" class="form-label">Tasca</label>
                        <input type="text" class="form-control" name="tasca" id="tasca" required>
                    </div>

                    <div class="mb-3">
                        <label for="despatx" class="form-label">Despatx</label>
                        <input type="text" class="form-control" name="despatx" id="despatx" required>
                    </div>

                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" name="dni" id="dni" required>
                    </div>

                    <div class="mb-3">
                        <label for="perfil" class="form-label">Perfil</label>
                        <input type="text" class="form-control" name="perfil" id="perfil" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Afegir Dades EPSEVG</button>

                    <!-- Boton para volver al indice -->
                    <div class="d-inline-flex gap-1">
                        <a href="index.php" class="btn" role="button" data-bs-toggle="button">Tornar</a>
                    </div>

                </form>
            </div>
        </div>


    </div>
</div>
