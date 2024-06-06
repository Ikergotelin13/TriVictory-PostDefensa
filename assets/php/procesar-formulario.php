<?php
session_start(); // Inicia la sesión si aún no se ha iniciado

$servidor = "localhost";
$usuario = "root";
$pass = "";
$bd = "pruebas";

$conexion = mysqli_connect($servidor, $usuario, $pass, $bd);

if (!$conexion) {
    echo "No se puede conectar con el servidor";
    exit;
}

// Manejar el formulario de inscripción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $categoria = $_POST['categoria'];
    $club = $_POST['club'];
    $genero = $_POST['genero']; 

    // Preparar la consulta para insertar el nuevo registro
    $query = "INSERT INTO participantes (nombre, apellidos, categoria, club, genero) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssss", $nombre, $apellidos, $categoria, $club, $genero); 

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro insertado correctamente";
        // Redirigir a otra página después de 2 segundos
        header("refresh:2;url=../../paginas/inscripcion/listado-participantes.php");
        exit;
    } else {
        echo "Error al insertar el registro: " . $conexion->error;
    }

    // Cerrar la conexión y detener la ejecución del resto del script
    $conexion->close();
    exit;
}

// Variables para paginación
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$max_registros = 9;
$inicio = ($pagina - 1) * $max_registros;

// Consulta los datos de la tabla participantes con paginación
if (isset($_GET['filtro-categoria']) && $_GET['filtro-categoria'] !== 'todos') {
    $query = "SELECT * FROM participantes WHERE categoria = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $_GET['filtro-categoria']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT * FROM participantes LIMIT ?, ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $inicio, $max_registros);
    $stmt->execute();
    $result = $stmt->get_result();
}

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

    $rows = array(); // Crear un array para almacenar los resultados de la consulta

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row; // Agregar cada fila al array
    }

    // Invertir el orden del array para mostrar los registros más recientes primero
    $rows = array_reverse($rows);

    // Mostrar los registros en la tabla
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['apellidos'] . "</td>";
        echo "<td>" . $row['categoria'] . "</td>";
        echo "<td>" . $row['club'] . "</td>";
        echo "<td>" . $row['genero'] . "</td>"; // Mostrar la columna 'Género'
        $clave_unica = $row['nombre'] . "_" . $row['apellidos'];
        echo "<td><form method='post' action='../../assets/php/eliminar-registro.php'><input type='hidden' name='clave_unica' value='" . $clave_unica . "'><button type='submit'>Eliminar</button></form></td>";
        echo "</tr>";
    }

    echo '</table>';

    // Calcular el número total de páginas
    $query_total = "SELECT COUNT(*) as total FROM participantes";
    $result_total = $conexion->query($query_total);
    $fila_total = $result_total->fetch_assoc();
    $total_registros = $fila_total['total'];
    $total_paginas = ceil($total_registros / $max_registros);

    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_paginas; $i++) {
        echo "<a href='#' onclick='loadPage($i)'";
        if ($i == $pagina) echo " class='active'";
        echo ">$i</a>";
    }
    echo '</div>';
} else {
    echo "Error al obtener los datos de la base de datos: " . $conexion->error;
}

$conexion->close();
?>
