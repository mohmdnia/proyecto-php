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
    $buscar = $_POST['buscar'];
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

<!-- Barra de búsqueda -->
<div class="busqueda">
    <nav class="d-flex justify-content-start mb-4">
        <form class="d-flex align-items-center" action="index.php" method="POST">
            <input type="text" class="form-control me-2" id="buscar" name="buscar" 
                value="<?php echo isset($_POST['buscar']) ? $_POST['buscar'] : ''; ?>" 
                placeholder="Buscar usuario..." style="width: 200px;">
            <input type="submit" class="btn btn-success ms-3" value="Ver">
        </form>
    </nav>
</div>

<!-- Nombre del empleado -->
<div class="nombre-empleado">
    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <span class="nombre">
            <strong><?php echo $row['nombre'] . " " . $row['apellido']; ?></strong>
        </span>

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

<main class="menu-container">
    <!-- Datos personales del empleado -->
    <div class="datosPersonales">
        <h3>Datos personales</h3>
        <dl>
            <?php
            if (mysqli_num_rows($query) > 0) {
                mysqli_data_seek($query, 0); // Reiniciar el cursor
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<dt>Nombre: </dt><dd>" . $row['nombre'] . "</dd>";
                    echo "<dt>Apellidos: </dt><dd>" . $row['apellido'] . "</dd>";
                    echo "<dt>Codigo Postal: </dt><dd>" . $row['codigo_postal'] . "</dd>";
                    echo "<dt>Telefono: </dt><dd>" . $row['telefono'] . "</dd>";
                    echo "<dt>Fecha de nacimiento: </dt><dd>" . $row['fecha_nacimiento'] . "</dd>";
                    echo "<dt>DNI/NIE: </dt><dd>" . $row['dni_nie'] . "</dd>";
                    echo "<dt>Población: </dt><dd>" . $row['poblacion'] . "</dd>";
                    echo "<dt>Sexo: </dt><dd>" . $row['sexo'] . "</dd>";
                }
            } else {
                echo "<p>No se ha encontrado ningún registro.</p>";
            }
            ?>
        </dl>
    </div>

    <!-- Datos Epsevg -->
    <div class="datosEpsevg">
        <h3>Datos EPSEVG</h3>
        <dl>
            <?php
            if (mysqli_num_rows($query) > 0) {
                mysqli_data_seek($query, 0);
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<dt>CIP: </dt><dd>" . $row['cip'] . "</dd>";
                    echo "<dt>Teléfono 1: </dt><dd>" . $row['telefono_1'] . "</dd>";
                    echo "<dt>Número de expediente: </dt><dd>" . $row['num_expediente'] . "</dd>";
                    echo "<dt>Categoría: </dt><dd>" . $row['categoria'] . "</dd>";
                    echo "<dt>Dedicacion: </dt><dd>" . $row['dedicacion'] . "</dd>";
                    echo "<dt>Departamento: </dt><dd>" . $row['departamento'] . "</dd>";
                    echo "<dt>Tarea: </dt><dd>" . $row['tarea'] . "</dd>";
                    echo "<dt>Email: </dt><dd>" . $row['email'] . "</dd>";
                    echo "<dt>Teléfono 2: </dt><dd>" . $row['telefono_2'] . "</dd>";
                    echo "<dt>Unidad estructural: </dt><dd>" . $row['unidad_estructural'] . "</dd>";
                    echo "<dt>Tipo asociado: </dt><dd>" . $row['tipo_asociado'] . "</dd>";
                    echo "<dt>Titulación: </dt><dd>" . $row['titulacion'] . "</dd>";
                    echo "<dt>Despacho: </dt><dd>" . $row['despacho'] . "</dd>";
                    echo "<dt>Perfil: </dt><dd>" . $row['perfil'] . "</dd>";
                }
            } else {
                echo "<p>No se ha encontrado ningún registro.</p>";
            }
            ?>
        </dl>
    </div>

    <!-- Grupos EPSEVG -->
    <div class="grupoEpsevg">
        <h3>Grupos EPSEVG</h3>
        <dl>
            <?php
            if (mysqli_num_rows($query) > 0) {
                mysqli_data_seek($query, 0);
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<dt>Nombre grupo: </dt><dd>" . $row['grupo_epsevg'] . "</dd>";
                }
            } else {
                echo "<p>Éste usuario no tiene grupos.</p>";
            }
            ?>
        </dl>
    </div>

    <!-- Usuarios relacionados -->
    <div class="usuariosRel">
        <h3>Usuarios relacionados</h3>
        <ul>
            <?php
            if (mysqli_num_rows($query) > 0) {
                mysqli_data_seek($query, 0);
                echo "<h5>Hay <strong>N</strong> usuarios relacionados por <strong>departamento</strong></h5><br>";
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<li>Grupo, Apellido, Nombre</li>";
                }
            } else {
                echo "<p>No hay usuarios relacionados.</p>";
            }
            ?>
        </ul>
    </div>
</main>
