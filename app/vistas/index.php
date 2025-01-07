<?php 
include_once "../modelo/database.php";
include_once "header.php";

// Verificar conexión
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Consulta para obtener el último empleado
$sql = "SELECT * FROM 340_personal ORDER BY id DESC LIMIT 1";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query); // Obtener el primer registro (último empleado)

// Consulta por defecto o por búsqueda
$sql = "SELECT * FROM 340_personal";
if (isset($_POST['buscar'])) {
    $buscar = mysqli_real_escape_string($conn, $_POST['buscar']); // Seguridad adicional para evitar SQL Injection
    $sql = "SELECT * FROM 340_personal WHERE nombre LIKE '%$buscar%'";
}
$query = mysqli_query($conn, $sql);

// Mostrar mensaje de éxito si existe
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show" role="alert" id="auto-close-alert">
        <?= htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php unset($_SESSION['message']); // Solo elimina el mensaje ?>
<?php endif; ?>

<!-- Código HTML -->
<body>

    <div class="layout">

        <!-- Barra de búsqueda -->

        <div class="busqueda">
            <form action="index.php" method="POST">
                <div class="input-group mb-1">
                    <select class="form-select" style="max-width: 150px;">
                        <option selected>Grupos</option>
                        <option value="1">PDI</option>
                        <option value="2">PTGAS</option>
                        <option value="3">EST</option>
                        <option value="4">BEC</option>
                        <option value="5">EXT</option>
                    </select>
                    <input type="text" name="buscar" placeholder="Buscar usuario..." class="form-control" value="<?= isset($_POST['buscar']) ? htmlspecialchars($_POST['buscar']) : ''; ?>" required>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        </div>


        <div class="contenedor">

            <?php if (isset($_POST['buscar'])): ?>
                <div class="nombre d-flex justify-content-between align-items-center">
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <h2>
                                <strong><?php echo htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']); ?></strong>
                            </h2>

                            <!-- Botones de CUD -->
                            <div class="botones ms-auto">
                                <a href="agregar.php" class="btn btn-success"><i class="bi bi-person-add"></i></a>
                                <a href="editar.php?id=<?= $row['id']; ?>" class="btn btn-secondary"><i class="bi bi-person-fill-gear"></i></a>
                                <a href="eliminar.php?id=<?= $row['id']; ?>" class="btn btn-danger"><i class="bi bi-person-dash-fill"></i></a>
                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No se han encontrado resultados para la búsqueda.</p>
                        <!-- Botones de CUD -->
                        <div class="botones ms-auto">
                            <a href="agregar.php" class="btn btn-success"><i class="bi bi-person-add"></i></a>
                            <a href="editar.php?id=<?= $row['id']; ?>" class="btn btn-secondary"><i class="bi bi-person-fill-gear"></i></a>
                            <a href="eliminar.php?id=<?= $row['id']; ?>" class="btn btn-danger"><i class="bi bi-person-dash-fill"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- ultimo empleado -->
                <?php if ($row): ?>
                    <div class="nombre">
                        <h2>
                            <strong><?php echo htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']); ?></strong>
                        </h2>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Datos personales -->
            <div class="section">
                <h2>Datos personales</h2>
                <?php
                if (mysqli_num_rows($query) > 0) {
                    mysqli_data_seek($query, 0);
                    $row = mysqli_fetch_assoc($query);
                ?>
                    <ul class="columnas">
                        <li><strong>Nombre: </strong><?= htmlspecialchars($row['nombre']); ?></li>
                        <li><strong>Apellidos: </strong><?= htmlspecialchars($row['apellido']); ?></li>
                        <li><strong>Código postal: </strong><?= htmlspecialchars($row['codigo_postal']); ?></li>
                        <li><strong>Teléfono móvil: </strong><?= htmlspecialchars($row['telefono']); ?></li>
                        <li><strong>Fecha de nacimiento: </strong><?= htmlspecialchars($row['fecha_nacimiento']); ?></li>
                        <li><strong>DNI/NIE: </strong><?= htmlspecialchars($row['dni_nie']); ?></li>
                        <li><strong>Población: </strong><?= htmlspecialchars($row['poblacion']); ?></li>
                        <li><strong>Sexo: </strong><?= htmlspecialchars($row['sexo']); ?></li>
                    </ul>
                <?php } else { ?>
                    <p>No se han encontrado datos personales.</p>
                <?php } ?>
            </div>

            <!-- Datos EPSEVG -->
            <div class="section">
                <h2>Datos EPSEVG</h2>

                <?php
                if (mysqli_num_rows($query) > 0) {
                    mysqli_data_seek($query, 0);
                    $row = mysqli_fetch_assoc($query);
                ?>
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
                <?php } else { ?>
                    <p>No se han encontrado datos epsevg.</p>
                <?php } ?>

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
