<?php
session_start();

$servidor = "localhost";
$usuario = "root";
$pass = "";
$bd = "pruebas";

$conexion = mysqli_connect($servidor, $usuario, $pass, $bd);

if (!$conexion){
    echo "Error al conectar con la base de datos";
    exit;
}

// Variables para paginación
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$max_registros = 9;
$inicio = ($pagina - 1) * $max_registros;

$palabra = isset($_GET['palabra']) ? $_GET['palabra'] : '';

// Consulta los datos de la tabla participantes con paginación
if (!empty($palabra)) {
    $query_count = "SELECT COUNT(*) as total FROM participantes WHERE nombre LIKE '%$palabra%' OR apellidos LIKE '%$palabra%' OR categoria LIKE '%$palabra%' OR club LIKE '%$palabra%' OR genero LIKE '%$palabra%'";
    $query = "SELECT * FROM participantes WHERE nombre LIKE '%$palabra%' OR apellidos LIKE '%$palabra%' OR categoria LIKE '%$palabra%' OR club LIKE '%$palabra%' OR genero LIKE '%$palabra%' LIMIT ?, ?";
} else {
    $query_count = "SELECT COUNT(*) as total FROM participantes";
    $query = "SELECT * FROM participantes LIMIT ?, ?";
}

// Obtener el total de registros
$resultado_count = mysqli_query($conexion, $query_count);
$fila_count = mysqli_fetch_assoc($resultado_count);
$total_registros = $fila_count['total'];
$total_paginas = ceil($total_registros / $max_registros);

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $inicio, $max_registros);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    echo '<style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding-left: 20px;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>';
    echo '<table>';
    echo '<tr><th>Nombre</th><th>Apellidos</th><th>Categoría</th><th>Club</th><th>Género</th><th>Acción</th></tr>';
    while ($row = $result->fetch_assoc()) {
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
    echo '</table>';

    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_paginas; $i++) {
        echo "<a href='?pagina=$i";
        if (!empty($palabra)) echo "&palabra=$palabra";
        echo "'";
        if ($i == $pagina) echo " class='active'";
        echo ">$i</a>";
    }
    echo '</div>';
} else {
    echo "Error al obtener los datos de la base de datos: " . $conexion->error;
}

$conexion->close();
?>
