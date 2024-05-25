<?php
session_start(); 

$servidor = "localhost";
$usuario = "root";
$pass = "";
$bd = "pruebas";

$conexion = mysqli_connect($servidor, $usuario, $pass, $bd);

if (!$conexion) {
    echo "No se puede conectar con el servidor";
    exit();
}

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$max_registros = 9;
$inicio = ($pagina - 1) * $max_registros;

if (isset($_GET['filtro-categoria']) && $_GET['filtro-categoria'] !== 'todos') {
    $query = "SELECT * FROM participantes WHERE categoria = ? LIMIT $inicio, $max_registros";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $_GET['filtro-categoria']);
    $stmt->execute();
    $result = $stmt->get_result();

    $query_total = "SELECT COUNT(*) as total FROM participantes WHERE categoria = ?";
    $stmt_total = $conexion->prepare($query_total);
    $stmt_total->bind_param("s", $_GET['filtro-categoria']);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
} else {
    $query = "SELECT * FROM participantes LIMIT $inicio, $max_registros";
    $result = mysqli_query($conexion, $query);

    $query_total = "SELECT COUNT(*) as total FROM participantes";
    $result_total = mysqli_query($conexion, $query_total);
}

if ($result):
    ?>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Categoría</th>
            <th>Club</th>
            <th>Género</th>
            <th>Acción</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['apellidos'] . "</td>";
            echo "<td>" . $row['categoria'] . "</td>";
            echo "<td>" . $row['club'] . "</td>";
            echo "<td>" . $row['genero'] . "</td>";
            $clave_unica = $row['nombre'] . "_" . $row['apellidos'];
            echo "<td><form method='post' action='../../assets/php/eliminar-registro.php'><input type='hidden' name='clave_unica' value='" . $clave_unica . "'><button type='submit'>Eliminar</button></form></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <?php
    $fila_total = mysqli_fetch_assoc($result_total);
    $total_registros = $fila_total['total'];
    $total_paginas = ceil($total_registros / $max_registros);
    ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="javascript:void(0);" onclick="cargarTabla(<?php echo $i; ?>)" <?php if ($i == $pagina) echo "class='active'"; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
    <?php
else:
    echo "Error al obtener los datos de la base de datos: " . mysqli_error($conexion);
endif;

$conexion->close();
?>
