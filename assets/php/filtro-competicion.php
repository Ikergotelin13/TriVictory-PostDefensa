<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "competiciones";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializa la consulta
$sql = "SELECT Modalidad, Competicion, Categoria, Distancia, Fecha, Enlace FROM listado WHERE 1";

// Filtrar por modalidad
if(isset($_POST['modalidad']) && $_POST['modalidad'] != 'Todas las modalidades') {
    $modalidad = $_POST['modalidad'];
    $sql .= " AND Modalidad = '$modalidad'";
}

// Filtrar por distancia
if(isset($_POST['distancia']) && $_POST['distancia'] != 'Todas las distancias') {
    $distancia = $_POST['distancia'];
    $sql .= " AND Distancia = '$distancia'";
}

// Filtrar por categoría
if(isset($_POST['categoria']) && $_POST['categoria'] != 'Todas las edades') {
    $categoria = $_POST['categoria'];
    $sql .= " AND Categoria = '$categoria'";
}

$result = $conn->query($sql);

// Crear un array asociativo para agrupar las competiciones por mes
$competiciones_por_mes = [];

if ($result->num_rows > 0) {
    // Si hay resultados, agrupar las competiciones por mes
    while ($row = $result->fetch_assoc()) {
        // Obtener el mes de la fecha y convertirlo a un formato legible (nombre del mes)
        $mes = date('F', strtotime($row["Fecha"]));

        // Crear un array si no existe para el mes actual
        if (!isset($competiciones_por_mes[$mes])) {
            $competiciones_por_mes[$mes] = [];
        }

        // Agregar la competición al array del mes correspondiente
        $competiciones_por_mes[$mes][] = $row;
    }

    // Ordenar los meses alfabéticamente
    ksort($competiciones_por_mes);
}

$output = '';

foreach ($competiciones_por_mes as $mes => $competiciones) {
    $output .= '<div id="container-listado-competiciones">';
    $output .= '<div class="Mes">';
    $output .= '<h4 id="mes-texto">' . $mes . ' 2024</h4>';
    $output .= '</div>';
    $output .= '<div id="listado-superior">';
    $output .= '<div class="Modalidad">Modalidad</div>';
    $output .= '<div class="Competicion">Competición</div>';
    $output .= '<div class="Categoria">Categoría</div>';
    $output .= '<div class="Distancia">Distancia</div>';
    $output .= '<div class="Fecha">Fecha</div>';
    $output .= '<div class="Enlace">Enlace</div>';
    $output .= '</div>';

    foreach ($competiciones as $competicion) {
        $output .= '<section class="competi">';
        $output .= '<div class="Modalidad">' . $competicion["Modalidad"] . '</div>';
        $output .= '<div class="Competicion">'. $competicion["Competicion"] . '</a></div>';
        $output .= '<div class="Categoria">' . $competicion["Categoria"] . '</div>';
        $output .= '<div class="Distancia">' . $competicion["Distancia"] . '</div>';
        $output .= '<div class="Fecha">' . $competicion["Fecha"] . '</div>';
        $output .= '<div class="Enlace"><a href="' . $competicion["Enlace"] . '"" class="enlaces-competiciones">Ver detalles</a></div>';
        $output .= '</section>';
    }

    $output .= '</div>';
}

echo $output;

$conn->close();
?>