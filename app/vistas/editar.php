<?php 
include_once "../modelo/database.php";
include_once "header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $conn;

    // Obtener el motivo del cambio
    $observacions = isset($_POST['observacions']) ? $_POST['observacions'] : '';
    if (empty($observacions)) {
        $_SESSION['message'] = 'El camp "Observacions" és obligatori.';
        $_SESSION['message_type'] = 'danger';
        header('Location: editar.php');
        exit;
    }

    // Datos enviados por el formulario
    $dni = isset($_POST['dni']) ? $_POST['dni'] : ''; 


    $new_data_personal = [];
    $new_data_epsevg = [];

    // Datos personales
    if (isset($_POST['nom'])) {
        $new_data_personal['nom'] = $_POST['nom'];
        $new_data_personal['cognoms'] = $_POST['cognoms'];
        $new_data_personal['cp'] = $_POST['cp'];
        $new_data_personal['telefon'] = $_POST['telefon'];
        $new_data_personal['telf_movil'] = $_POST['telf_movil'];
        $new_data_personal['data_naixement'] = $_POST['data_naixement'];
        $new_data_personal['poblacio'] = $_POST['poblacio'] ?? null;
        $new_data_personal['sexe'] = $_POST['sexe'];
    }

    // Datos EPSEVG
    if (isset($_POST['cip'])) {
        $new_data_epsevg['cip'] = $_POST['cip'];
        $new_data_epsevg['telf1'] = $_POST['telf1'];
        $new_data_epsevg['numero_expedient'] = $_POST['numero_expedient'];
        $new_data_epsevg['incid'] = $_POST['incid'] ?? null;
        $new_data_epsevg['categoria'] = $_POST['categoria'];
        $new_data_epsevg['dedicacio'] = $_POST['dedicacio'];
        $new_data_epsevg['departament'] = $_POST['departament'];
        $new_data_epsevg['tasca'] = $_POST['tasca'];
        $new_data_epsevg['unitat_estructural'] = $_POST['unitat_estructural'];
        $new_data_epsevg['tipus_associat'] = $_POST['tipus_associat'] ?? null;
        $new_data_epsevg['titulacio'] = $_POST['titulacio'];
        $new_data_epsevg['despatx'] = $_POST['despatx'];
        $new_data_epsevg['perfil'] = $_POST['perfil'];
    }

    // Obtener datos actuales del usuario
    function get_current_data($conn, $dni, $table) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE dni = ?");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    $current_data_personal = get_current_data($conn, $dni, '340_personal');
    $current_data_epsevg = get_current_data($conn, $dni, '340_personal_epsevg');
    
    // Comparar datos y registrar cambios
    function log_changes($conn, $dni, $field, $old_value, $new_value, $observacions) {
        $old_value = $old_value ?? '';
        $new_value = $new_value ?? ''; 

        $sql = "INSERT INTO 340_personal_historic (dni, camp_canviat, valor_antic, valor_nou, observacions, data_efectiva) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $dni, $field, $old_value, $new_value, $observacions);
        $stmt->execute();
    }

    // Registrar cambios para datos personales
    foreach ($new_data_personal as $field => $new_value) {
        $old_value = $current_data_personal[$field] ?? null;
        if ($new_value !== $old_value) {
            log_changes($conn, $dni, $field, $old_value, $new_value, $observacions);
        }
    }

    // Registrar cambios para datos EPSEVG
    foreach ($new_data_epsevg as $field => $new_value) {
        $old_value = $current_data_epsevg[$field] ?? null;
        if ($new_value !== $old_value) {
            log_changes($conn, $dni, $field, $old_value, $new_value, $observacions);
        }
    }

    // Actualización de los datos en las tablas principales
    function update_table($conn, $table, $data, $dni) {
        if (empty($data)) {
            return; // No actualizar si no hay datos
        }

        $set_clause = implode(", ", array_map(fn($field) => "$field = ?", array_keys($data)));
        $sql = "UPDATE $table SET $set_clause WHERE dni = ?";
        $stmt = $conn->prepare($sql);

        // Combinar los valores del array con $dni
        $params = array_merge(array_values($data), [$dni]);

        // Generar los tipos de parámetros (s para string)
        $types = str_repeat("s", count($params));

        // Usar call_user_func_array para pasar los argumentos a bind_param 
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
    }

    update_table($conn, '340_personal', $new_data_personal, $dni);
    update_table($conn, '340_personal_epsevg', $new_data_epsevg, $dni);

    // Mensaje de éxito
    $_SESSION['message'] = 'Dades actualitzades amb èxit.';
    $_SESSION['message_type'] = 'success';
    header('Location: editar.php?dni=' . $dni); 
    exit;
}
?>

<!-- codigo html -->
<div class="container mt-5">
    
    <h2>Actualitzar Empleat</h2>
    <!-- Formulario de actualizar el empleado -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card bg-light p-3">
                    <form method="POST" action="editar.php">
                        <!-- Datos Personales -->
                        <h5 class="mt-4">Dades Personals</h5>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" id="nom" required value="<?= $empleat['nom'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="cognoms" class="form-label">Cognoms</label>
                            <input type="text" class="form-control" name="cognoms" id="cognoms" required value="<?= $empleat['cognoms'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select class="form-control" name="sexe" id="sexe" required>
                                <option value="M" <?= (isset($empleat['sexe']) && $empleat['sexe'] == 'M') ? 'selected' : '' ?>>Masculí</option>
                                <option value="F" <?= (isset($empleat['sexe']) && $empleat['sexe'] == 'F') ? 'selected' : '' ?>>Femení</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="domicili" class="form-label">Domicili</label>
                            <input type="text" class="form-control" name="domicili" id="domicili" required value="<?= $empleat['domicili'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="cp" class="form-label">Codi Postal</label>
                            <input type="text" class="form-control" name="cp" id="cp" required value="<?= $empleat['cp'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telefon" class="form-label">Telefon</label>
                            <input type="text" class="form-control" name="telefon" id="telefon" required value="<?= $empleat['telefon'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telf_movil" class="form-label">Telefon Movil</label>
                            <input type="text" class="form-control" name="telf_movil" id="telf_movil" required value="<?= $empleat['telf_movil'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="data_naixement" class="form-label">Data de Naixement</label>
                            <input type="date" class="form-control" name="data_naixement" id="data_naixement" required value="<?= $empleat['data_naixement'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI/NIE</label>
                            <input type="text" class="form-control" name="dni" id="dni" required value="<?= $empleat['dni'] ?? '' ?>">
                        </div>

                        <!-- Datos EPSEVG -->
                        <h5 class="mt-4">Dades EPSEVG</h5>
                        <div class="mb-3">
                            <label for="cip" class="form-label">CIP</label>
                            <input type="text" class="form-control" name="cip" id="cip" required value="<?= $epsevg['cip'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telf1" class="form-label">Telefon 1</label>
                            <input type="text" class="form-control" name="telf1" id="telf1" required value="<?= $epsevg['telf1'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telf2" class="form-label">Telefon 2</label>
                            <input type="text" class="form-control" name="telf2" id="telf2" required value="<?= $epsevg['telf2'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required value="<?= $epsevg['email'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="inicid" class="form-label">Inicid</label>
                            <input type="inicid" class="form-control" name="inicid" id="inicid" required value="<?= $epsevg['inicid'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="numero_expedient" class="form-label">Número de expedient</label>
                            <input type="text" class="form-control" name="numero_expedient" id="numero_expedient" required value="<?= $epsevg['numero_expedient'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" name="categoria" id="categoria" required value="<?= $epsevg['categoria'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="titulacio" class="form-label">Titulació</label>
                            <input type="text" class="form-control" name="titulacio" id="titulacio" required value="<?= $epsevg['titulacio'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="departament" class="form-label">Departament</label>
                            <input type="text" class="form-control" name="departament" id="departament" required value="<?= $epsevg['departament'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="unitat_estructural" class="form-label">Unitat estructural</label>
                            <input type="text" class="form-control" name="unitat_estructural" id="unitat_estructural" required value="<?= $epsevg['unitat_estructural'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tasca" class="form-label">Tasca</label>
                            <input type="text" class="form-control" name="tasca" id="tasca" required value="<?= $epsevg['tasca'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="despatx" class="form-label">Despatx</label>
                            <input type="text" class="form-control" name="despatx" id="despatx" required value="<?= $epsevg['despatx'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tipus_associat" class="form-label">Tipus Associat</label>
                            <input type="text" class="form-control" name="tipus_associat" id="tipus_associat" required value="<?= $epsevg['tipus_associat'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="dedicacio" class="form-label">Dedicació</label>
                            <input type="text" class="form-control" name="dedicacio" id="dedicacio" required value="<?= $epsevg['dedicacio'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="perfil" class="form-label">Perfil</label>
                            <input type="text" class="form-control" name="perfil" id="perfil" required value="<?= $epsevg['perfil'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="observacions" class="form-label">Motiu del canvi</label>
                            <textarea class="form-control" name="observacions" id="observacions" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Dades</button>
                        <a href="index.php" class="btn btn-secondary">Tornar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
