<?php 
include_once "../modelo/database.php";
include_once "header.php";

// Verificar conexión
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}


// Variables iniciales
$busqueda = "";
$grupo = "TOT";
$resultado = null;
$mensaje = "";
$usuariosRelacionados = []; // Aquí almacenaremos los usuarios relacionados

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $busqueda = mysqli_real_escape_string($conn, trim($_POST['buscar'])); // Escapar entrada del usuario
    $grupo = isset($_POST['grupo']) ? mysqli_real_escape_string($conn, $_POST['grupo']) : 'TOT';

    // Dividir la búsqueda en nombre y apellido (si hay un espacio)
    $partes = explode(' ', $busqueda, 2); 
    $nom = $partes[0];
    $cognoms = isset($partes[1]) ? $partes[1] : '';

    // Consulta SQL con JOIN para obtener datos de ambas tablas
    $sql = "
    SELECT 
        p.*, 
        e.*, 
        c.nom AS categoria, 
        t.nom AS titulacio
    FROM 340_personal AS p
    LEFT JOIN 340_personal_epsevg AS e ON p.dni = e.dni
    LEFT JOIN 340_personal_categories AS c ON e.categoria = c.id
    LEFT JOIN 340_personal_titulacions AS t ON e.titulacio = t.id
    WHERE p.nom LIKE '%$nom%' AND p.cognoms LIKE '%$cognoms%'
    ";

    // Filtrar usuarios por grupo si se selecciona uno específico
    if ($grupo !== 'TOT') {
        $sql .= " AND e.perfil = '$grupo'";
    }
    
    $query = mysqli_query($conn, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $resultado = mysqli_fetch_assoc($query); // Obtener el primer resultado

        // Obtener usuarios relacionados según el mismo departamento y perfil
        if (!empty($resultado['departament']) && !empty($resultado['perfil'])) {
            $departament = mysqli_real_escape_string($conn, $resultado['departament']);
            $perfil = mysqli_real_escape_string($conn, $resultado['perfil']);

            $sqlRelacionados = "
                SELECT p.nom, p.cognoms, e.perfil
                FROM 340_personal AS p
                LEFT JOIN 340_personal_epsevg AS e ON p.dni = e.dni
                WHERE e.departament = '$departament' 
                AND e.perfil = '$perfil'  /* Filtrar por perfil */
                AND p.dni != '" . mysqli_real_escape_string($conn, $resultado['dni']) . "' 
            ";

            $queryRelacionados = mysqli_query($conn, $sqlRelacionados);

            if ($queryRelacionados && mysqli_num_rows($queryRelacionados) > 0) {
                while ($relacionado = mysqli_fetch_assoc($queryRelacionados)) {
                    $usuariosRelacionados[] = $relacionado;
                }
            }
        }
    } else {
        $mensaje = "No hi ha registres";
    }
}
?>


<!-- codigo html -->
<body>
    <div class="layout">

        <!-- Barra de búsqueda -->
        <div class="busqueda-fondo">
            <div class="busqueda">
                <form action="index.php" method="POST">
                    <div class="input-group mb-1">
                        <select class="form-select" name="grupo" style="max-width: 150px;">
                            <option value="TOT" <?= $grupo === "TOT" ? "selected" : "" ?>>TOT</option>
                            <option value="pdi" <?= $grupo === "pdi" ? "selected" : "" ?>>PDI</option>
                            <option value="pas" <?= $grupo === "pas" ? "selected" : "" ?>>PAS</option>
                            <option value="est" <?= $grupo === "est" ? "selected" : "" ?>>EST</option>
                            <option value="bec" <?= $grupo === "bec" ? "selected" : "" ?>>BEC</option>
                            <option value="ext" <?= $grupo === "ext" ? "selected" : "" ?>>EXT</option>
                        </select>

                        <input type="text" name="buscar" placeholder="Buscar usuari..." value="<?php echo $_POST['buscar'] ?? ''; ?>" class="form-control">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="resultados">
                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <div class="resultado-item">
                            <!-- Etiqueta del grupo -->
                            <span class="badge badge-<?= htmlspecialchars($row['perfil']); ?>">
                                <?= htmlspecialchars($row['perfil']); ?>
                            </span>
                            <!-- Nombre del usuario -->
                            <span>
                                <?= htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['cognoms']); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="contenedor">
            <div class="nombre d-flex justify-content-between align-items-center">
                <?php if (isset($resultado) && $resultado !== null): ?>
                    <!-- Mostrar nombre y apellido -->
                    <h2>
                        <strong>
                            <?= htmlspecialchars($resultado['nom']) . ' ' . htmlspecialchars($resultado['cognoms']); ?>
                        </strong>
                        <span class="badge badge-<?= htmlspecialchars($resultado['perfil'] ?? 'default'); ?>">
                            <?= htmlspecialchars($resultado['perfil'] ?? 'No definido'); ?>
                        </span>
                    </h2>
                <?php elseif (isset($mensaje)): ?>
                    <!-- Mostrar mensaje de error si no se encuentra -->
                    <h5><?= htmlspecialchars($mensaje); ?></h5>

                <?php endif; ?>

                <div class="botones ms-auto">
                    <a href="agregar.php" class="btn btn-success"><i class="bi bi-person-add"></i></a>
                    <a href="editar.php" class="btn btn-secondary"><i class="bi bi-person-fill-gear"></i></a>
                    <a href="eliminar.php?dni=<?= htmlspecialchars($resultado['dni']); ?>" class="btn btn-danger"><i class="bi bi-person-dash-fill"></i></a>
                    <a href="baja.php" class="btn btn-primary btn-lg active"><i class="bi bi-person-fill-down"></i></a>
                </div>
            </div>

                                <!-- Datos personales -->
            <div class="section">
                <h2>Dades personals</h2>
                
                <ul class="columnas">
                <?php if (isset($resultado)): ?>
                    <li><strong>Nom: </strong><?= htmlspecialchars($resultado['nom']); ?></li>
                    <li><strong>Cognoms: </strong><?= htmlspecialchars($resultado['cognoms']); ?></li>
                    <li><strong>Codi postal: </strong><?= htmlspecialchars($resultado['cp']); ?></li>
                    <li><strong>Telefon: </strong><?= htmlspecialchars($resultado['telefon']); ?></li>
                    <li><strong>Telefon Movil: </strong><?= htmlspecialchars($resultado['telf_movil']); ?></li>
                    <li><strong>Data de naixement: </strong><?= htmlspecialchars($resultado['data_naixement']); ?></li>
                    <li><strong>DNI/NIE: </strong><?= htmlspecialchars($resultado['dni']); ?></li>
                    <li><strong>Sexe: </strong><?= htmlspecialchars($resultado['sexe']); ?></li>

                <?php elseif (isset($mensaje)): ?>
                    <h5><?= htmlspecialchars($mensaje); ?></h5>
                <?php endif; ?>

                </ul>
            </div>
            

            <!-- Datos EPSEVG -->
            <div class="section">
                <h2>Datos EPSEVG</h2>

                <ul class="columnas">
                <?php if (isset($resultado)): ?>
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
                <?php elseif (isset($mensaje)): ?>
                    <h5><?= htmlspecialchars($mensaje); ?></h5>
                <?php endif; ?>
                </ul>
            </div>

            <!-- Grupos de usuario -->
            <div class="section">
                <h2>Grups EPSEVG</h2>

                <ul class="columnas">
                    <li><h5><strong>No hi ha registres</strong></h5></li>
                </ul>

            </div>

            <!-- Usuarios relacionados -->
            <div class="section">
                <h2>Usuarios relacionados</h2>
                <ul class="columnas">
                    <?php if (!empty($usuariosRelacionados)): ?>
                        <li><strong>Hay <?= count($usuariosRelacionados); ?> usuarios relacionados por departamento:</strong></li>
                        <?php foreach ($usuariosRelacionados as $usuario): ?>
                            <li>
                                <!-- Verificar que los valores existan antes de mostrarlos -->
                                <span class="badge badge-<?= isset($usuario['perfil']) ? htmlspecialchars($usuario['perfil']) : 'default'; ?>">
                                    <?= isset($usuario['perfil']) ? htmlspecialchars($usuario['perfil']) : 'No definido'; ?>
                                </span>
                                <span>
                                    <?= isset($usuario['nom']) && isset($usuario['cognoms']) ? htmlspecialchars($usuario['nom']) . ' ' . htmlspecialchars($usuario['cognoms']) : 'Nombre no disponible'; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No hay usuarios relacionados por departamento.</li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </div>

</body>
</html>
