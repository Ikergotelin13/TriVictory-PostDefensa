<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "resultados";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener todas las competiciones ordenadas por fecha
$sql = "SELECT Modalidad, Competicion, Categoria, Distancia, Fecha, Enlace FROM listado ORDER BY Fecha";

$result = $conn->query($sql);

// Crear un array asociativo para agrupar las competiciones por mes
$competiciones_por_mes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mes = date('F', strtotime($row["Fecha"]));
        if (!isset($competiciones_por_mes[$mes])) {
            $competiciones_por_mes[$mes] = [];
        }
        $competiciones_por_mes[$mes][] = $row;
    }
}

// Construir el HTML de los resultados agrupados por mes
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
        $output .= '<div class="Competicion">'. $competicion["Competicion"] . '</div>';
        $output .= '<div class="Categoria">' . $competicion["Categoria"] . '</div>';
        $output .= '<div class="Distancia">' . $competicion["Distancia"] . '</div>';
        $output .= '<div class="Fecha">' . $competicion["Fecha"] . '</div>';
        $output .= '<div class="Enlace"><a href="' . $competicion["Enlace"] . '" class="enlaces-competiciones">Ver detalles</a></div>';
        $output .= '</section>';
    }

    $output .= '</div>';
}

if (empty($competiciones_por_mes)) {
    $output .= '<div class="mensaje-vacio">';
    $output .= '<p>No se encontraron competiciones.</p>';
    $output .= '</div>';
}

echo $output;

$conn->close();
?>
