<?php
include_once "../modelo/database.php";
include_once "header.php";

// Verificar conexión
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Consulta por defecto
$sql = "SELECT * FROM 340_personal";
if (isset($_POST['buscar'])) {
    $buscar = mysqli_real_escape_string($conn, $_POST['buscar']); // Seguridad adicional para evitar SQL Injection
    $sql = "SELECT * FROM 340_personal WHERE nombre LIKE '%$buscar%'";
}
$query = mysqli_query($conn, $sql);
?>

<?php if (isset($_SESSION['message'])) { ?>
    <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php session_unset(); } ?>

<!-- Código HTML -->
<body>

    <div class="layout">

        <!-- barra de busqueda -->
        <div class="busqueda">
            <form action="index.php" method="POST">
                <input type="text" name="buscar" placeholder="Buscar usuario..." class="search-input"
                    value="<?= isset($_POST['buscar']) ? htmlspecialchars($_POST['buscar']) : ''; ?>">
                <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="contenedor">
            <!-- Nombre del perfil -->
                
            <div class="nombre">
                <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                    <h2>
                        <strong><?php echo htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']); ?></strong>
                    </h2>
                    <!-- Botones de CUD -->
                    <div class="botones">
                        <a href="agregar.php" class="btn btn-success"><i class="bi bi-person-add"></i></a>
                        <a href="editar.php?id=<?= $row['id']; ?>" class="btn btn-secondary">
                            <i class="bi bi-person-fill-gear"></i>
                        </a>
                        <a href="eliminar.php?id=<?= $row['id']; ?>" class="btn btn-danger">
                            <i class="bi bi-person-dash-fill"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <!-- Datos personales -->
            <div class="section">
                <h2>Datos personales</h2>
                <ul class="columnas">
                    <li><strong>Nombre: </strong> *** </li>
                    <li><strong>Apellidos: </strong> *** </li>
                    <li><strong>DNI/NIE: </strong> *** </li>
                    <li><strong>Dirección postal: </strong> *** </li>
                    <li><strong>Población: </strong> *** </li>
                    <li><strong>Teléfono movil: </strong> *** </li>
                    <li><strong>Teléfono: </strong> *** </li>
                    <li><strong>Sexo: </strong> *** </li>
                </ul>
            </div>

            <!-- Datos EPSEVG -->
            <div class="section">
                <h2>Datos EPSEVG</h2>
                <ul class="columnas">
                    <li><strong>CIP: </strong> *** </li>
                    <li><strong>Teléfono 1: </strong> *** </li>
                    <li><strong>Número de expediente: </strong> *** </li>
                    <li><strong>Categoría: </strong> *** </li>
                    <li><strong>Dedicación: </strong> *** </li>
                    <li><strong>Departamento: </strong> *** </li>
                    <li><strong>Tarea: </strong> *** </li>
                    <li><strong>Email: </strong> *** </li>
                    <li><strong>Teléfono 2: </strong> *** </li>
                    <li><strong>Unidad estructural: </strong> *** </li>
                    <li><strong>Tipo asociado: </strong> *** </li>
                    <li><strong>Titulación: </strong> *** </li>
                    <li><strong>Despacho: </strong> *** </li>
                    <li><strong>Perfil: </strong> *** </li>
                </ul>
            </div>

            <!-- Grupos de usuario -->
            <div class="section">
                <h2>Grupos EPSEVG</h2>
                <ul>
                    <li>Éste usuario no tiene grupos.</li>
                </ul>
            </div>

            <!-- Usuarios relacionados -->
            <div class="section">
                <h2>Usuarios relacionados</h2>
                <ul>
                    <li>Éste usuario no tiene usuarios relacionados por grupo.</li>
                </ul>
            </div>

        </div>

    </div>

</body>
</html>
