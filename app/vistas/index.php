<?php 
include_once "../modelo/database.php";
include_once "header.php";

// Verificar conexión
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}
?>

<!-- barra de busqueda -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $busqueda = mysqli_real_escape_string($conn, trim($_POST['buscar'])); // Escapar entrada del usuario

    // Dividir la búsqueda en nombre y apellido (si hay un espacio)
    $partes = explode(' ', $busqueda, 2); 
    $nom = $partes[0];
    $cognoms = isset($partes[1]) ? $partes[1] : '';

    // Consulta SQL con JOIN para obtener datos de ambas tablas
    $sql = "
        SELECT p.*, e.*
        FROM 340_personal AS p
        LEFT JOIN 340_personal_epsevg AS e ON p.dni = e.dni
        WHERE p.nom LIKE '%$nom%' AND p.cognoms LIKE '%$cognoms%'
    ";
    $query = mysqli_query($conn, $sql);

    // Verificar resultados
    if ($query && mysqli_num_rows($query) > 0) {
        $resultado = mysqli_fetch_assoc($query);
    } else {
        $mensaje = "No se ha encontrado ningún usuario con ese nombre y apellido";
    }
}
?>


<!-- codigo html -->
<body>
    <div class="layout">

        <!-- Barra de búsqueda -->
        <div class="busqueda">
            <form action="index.php" method="POST">
                <div class="input-group mb-1">
                    <select class="form-select" style="max-width: 150px;">
                        <option selected>Perfils</option>
                        <option value="pdi">PDI</option>
                        <option value="ptgas">PTGAS</option>
                        <option value="est">EST</option>
                        <option value="bec">BEC</option>
                        <option value="ext">EXT</option>
                    </select>

                    <input type="text" name="buscar" placeholder="Buscar usuari..." value="<?php echo $_POST["buscar"] ?>" class="form-control" required>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        </div>

        <div class="contenedor">

            <div class="nombre d-flex justify-content-between align-items-center">
                <?php if (isset($resultado)): ?>
                    <!-- Mostrar nombre y apellido -->
                    <h2><strong><?= htmlspecialchars($resultado['nom']) . ' ' . htmlspecialchars($resultado['cognoms']); ?></strong></h2>

                <?php elseif (isset($mensaje)): ?>
                    <!-- Mostrar mensaje de error si no se encuentra -->
                    <h4><?= htmlspecialchars($mensaje); ?></h4>

                <?php endif; ?>

                <div class="botones ms-auto">
                    <a href="agregar.php" class="btn btn-success"><i class="bi bi-person-add"></i></a>
                    <a href="editar.php" class="btn btn-secondary"><i class="bi bi-person-fill-gear"></i></a>
                    <a href="eliminar.php" class="btn btn-danger"><i class="bi bi-person-dash-fill"></i></a>
                </div>
            </div>

            <!-- Datos personales -->
            <div class="section">
                <h2>Dades personals</h2>
                
                <?php if (isset($resultado)): ?>
                    <ul class = "columnas">
                        <li><strong>Nom: </strong><?= htmlspecialchars($resultado['nom']); ?></li>
                        <li><strong>Cognoms: </strong><?= htmlspecialchars($resultado['cognoms']); ?></li>
                        <li><strong>Codi postal: </strong><?= htmlspecialchars($resultado['cp']); ?></li>
                        <li><strong>Telefon: </strong><?= htmlspecialchars($resultado['telefon']); ?></li>
                        <li><strong>Telefon Movil: </strong><?= htmlspecialchars($resultado['telf_movil']); ?></li>
                        <li><strong>Data de naixement: </strong><?= htmlspecialchars($resultado['data_naixement']); ?></li>
                        <li><strong>DNI/NIE: </strong><?= htmlspecialchars($resultado['dni']); ?></li>
                        <li><strong>Sexe: </strong><?= htmlspecialchars($resultado['sexe']); ?></li>
                    </ul>
                <?php elseif (isset($mensaje)): ?>
                    <h4><?= htmlspecialchars($mensaje); ?></h4>
                <?php endif; ?>

            </div>

            <!-- Datos EPSEVG -->
            <div class="section">
                <h2>Datos EPSEVG</h2>

                <?php if (isset($resultado)): ?>
                    <ul class="columnas">
                        <li><strong>CIP: </strong><?= htmlspecialchars($resultado['cip']); ?></li>
                        <li><strong>Telefon 1: </strong><?= htmlspecialchars($resultado['telf1']); ?></li>
                        <li><strong>Número de expedient: </strong><?= htmlspecialchars($resultado['numero_expedient']); ?></li>
                        <li><strong>Categoria: </strong><?= htmlspecialchars($resultado['categoria']); ?></li>
                        <li><strong>Dedicació: </strong><?= htmlspecialchars($resultado['dedicacio']); ?></li>
                        <li><strong>Departament: </strong><?= htmlspecialchars($resultado['departament']); ?></li>
                        <li><strong>Tasca: </strong><?= htmlspecialchars($resultado['tasca']); ?></li>
                        <li><strong>Email: </strong><?= htmlspecialchars($resultado['email']); ?></li>
                        <li><strong>Telefon 2: </strong><?= htmlspecialchars($resultado['telf2']); ?></li>
                        <li><strong>Unitat estructural: </strong><?= htmlspecialchars($resultado['cip']); ?></li>
                        <li><strong>Tipus associat: </strong><?= htmlspecialchars($resultado['tipus_associat']); ?></li>
                        <li><strong>Titulació: </strong><?= htmlspecialchars($resultado['titulacio']); ?></li>
                        <li><strong>Despatx: </strong><?= htmlspecialchars($resultado['despatx']); ?></li>
                        <li><strong>Perfil: </strong><?= htmlspecialchars($resultado['perfil']); ?></li>
                    </ul>
                <?php elseif (isset($mensaje)): ?>
                    <h4><?= htmlspecialchars($mensaje); ?></h4>
                <?php endif; ?>

            </div>

            <!-- Grupos de usuario -->
            <div class="section">
                <h2>Grups EPSEVG</h2>

                <ul class="columnas">
                    <li><strong>No hi ha registres</strong></li>
                </ul>

            </div>

            <!-- Usuarios relacionados -->
            <div class="section">
                <h2>Usuaris relacionats</h2>
                <ul class="columnas">
                    <li>Aquest usuari no té usuaris relacionats per grup.</li>
                </ul>
            </div>

        </div>
    </div>

</body>
</html>
