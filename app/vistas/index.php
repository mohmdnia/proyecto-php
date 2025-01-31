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
$usuariosRelacionados = []; // Aqui almacenaremos los usuarios relacionados
$query = null;

// Mensajes para grupos
$idGrupo = 2;

// Consultar el nombre del grupo
$sqlGrupo = "SELECT nom FROM 340_personal_grups WHERE id = $idGrupo";
$queryGrupo = mysqli_query($conn, $sqlGrupo);
$grupoNombre = ($queryGrupo && mysqli_num_rows($queryGrupo) > 0) ? mysqli_fetch_assoc($queryGrupo)['nom'] : 'Desconocido';

// Consulta para obtener las personas del grupo
$sqlPersonas = "
    SELECT p.nom, p.cognoms, e.perfil 
    FROM 340_personal AS p
    INNER JOIN 340_personal_grups_pertany AS pgp ON p.dni = pgp.dni
    INNER JOIN 340_personal_grups AS g ON pgp.idgrup = g.id
    LEFT JOIN 340_personal_epsevg AS e ON p.dni = e.dni
    WHERE g.nom = 'cap de seccio'"; 

// Filtrar por perfil si no es 'TOT'
if ($grupo !== 'TOT') {
    $sqlPersonas .= " AND e.perfil = '$grupo'";
}

$queryPersonas = mysqli_query($conn, $sqlPersonas);
$personas = [];
if ($queryPersonas && mysqli_num_rows($queryPersonas) > 0) {
    while ($row = mysqli_fetch_assoc($queryPersonas)) {
        $personas[] = $row;
    }
}
$cantidadPersonas = count($personas);




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $busqueda = mysqli_real_escape_string($conn, trim($_POST['buscar'])); 
    $grupo = isset($_POST['grupo']) ? mysqli_real_escape_string($conn, $_POST['grupo']) : 'TOT';

    // Dividir la búsqueda en nombre y apellido
    $partes = explode(' ', $busqueda, 2); 
    $nom = $partes[0];
    $cognoms = isset($partes[1]) ? $partes[1] : '';


// Comprobar si la búsqueda es un número (posible DNI)
if (is_numeric($busqueda)) {
    // Si es un número, la búsqueda es por DNI
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
        WHERE p.dni LIKE '%$busqueda%'"; // Buscar por DNI
} else {
    // Si no es un número, la búsqueda es por nombre y apellido
    $partes = explode(' ', $busqueda, 2); 
    $nom = $partes[0];
    $cognoms = isset($partes[1]) ? $partes[1] : '';

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
        WHERE p.nom LIKE '%$nom%' AND p.cognoms LIKE '%$cognoms%'";
}

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
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <select class="form-select" name="grupo" style="max-width: 150px;">
                            <option value="TOT" <?= $grupo === "TOT" ? "selected" : "" ?>>TOT</option>
                            <option value="pdi" <?= $grupo === "pdi" ? "selected" : "" ?>>PDI</option>
                            <option value="pas" <?= $grupo === "pas" ? "selected" : "" ?>>PAS</option>
                            <option value="est" <?= $grupo === "est" ? "selected" : "" ?>>EST</option>
                            <option value="bec" <?= $grupo === "bec" ? "selected" : "" ?>>BEC</option>
                            <option value="ext" <?= $grupo === "ext" ? "selected" : "" ?>>EXT</option>
                        </select>
                        <input type="text" name="buscar" placeholder="Buscar usuario o DNI..." value="<?= htmlspecialchars($_POST['buscar'] ?? '') ?>" class="form-control">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="resultados">
                <?php if ($query && mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <div class="resultado-item">
                            <span class="badge badge-<?= htmlspecialchars($row['perfil']); ?>">
                                <?= htmlspecialchars($row['perfil']); ?>
                            </span>
                            <span>
                                <?= htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['cognoms']); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="mensaje-busqueda">
                        <h5>🔍 No s'han trobat resultats</h5>
                        <h7>Intenta-ho amb un altre nom o DNI</h7>
                    </div>
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
            <?php if ($cantidadPersonas > 0): ?>
                <div class="section">
                    <h2>Grups EPSEVG</h2>
                    <div class="grupo-info">
                        <h3>Grup: <?= htmlspecialchars($grupoNombre); ?></h3>
                        <p>Quantitat de persones: <strong><?= $cantidadPersonas; ?></strong></p>
                    </div>
                    <div class="row">
                        <?php foreach ($personas as $persona): ?>
                            <div class="col-md-4 mb-4">
                                <div class="persona-card border p-3 rounded shadow-sm">
                                    <span class="badge badge-<?= isset($persona['perfil']) ? htmlspecialchars($persona['perfil']) : 'default'; ?>">
                                        <?= isset($persona['perfil']) ? htmlspecialchars($persona['perfil']) : 'No definido'; ?>
                                    </span>
                                    <span>
                                        <?= isset($persona['nom']) && isset($persona['cognoms']) ? htmlspecialchars($persona['nom']) . ' ' . htmlspecialchars($persona['cognoms']) : 'Nombre no disponible'; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No hay personas en este grupo.</p>
            <?php endif; ?>
        
        </div>

            <!-- Usuarios relacionados -->
            <?php if (!empty($usuariosRelacionados)): ?>
                <div class="section">
                    <h2>Usuaris relacionats</h2>
                    <p><strong>Hi ha <?= count($usuariosRelacionados); ?> usuaris relacionats per departament:</strong></p>
                    <div class="row">
                        <?php foreach ($usuariosRelacionados as $usuario): ?>
                            <div class="col-md-4 mb-4">
                                <div class="persona-card border p-3 rounded shadow-sm">
                                    <span class="badge badge-<?= isset($usuario['perfil']) ? htmlspecialchars($usuario['perfil']) : 'default'; ?>">
                                        <?= isset($usuario['perfil']) ? htmlspecialchars($usuario['perfil']) : 'No definido'; ?>
                                    </span>
                                    <span>
                                        <?= isset($usuario['nom']) && isset($usuario['cognoms']) ? htmlspecialchars($usuario['nom']) . ' ' . htmlspecialchars($usuario['cognoms']) : 'Nombre no disponible'; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="section">
                    <h2>Usuaris relacionats</h2>
                    <p><strong><?= htmlspecialchars($mensaje); ?></strong></p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
