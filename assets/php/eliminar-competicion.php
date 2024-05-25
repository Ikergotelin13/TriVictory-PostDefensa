<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Verificar si el campo 'competicion' está definido en $_POST
if (isset($_POST['competicion'])) {
    // Obtener el valor de 'competicion'
    $competicion = $_POST['competicion'];

    // Preparar la consulta SQL
    $stmt = $conn->prepare("DELETE FROM listado WHERE Competicion = ?");
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }

    $stmt->bind_param("s", $competicion);

    // Ejecutar la consulta
    if ($stmt->execute() === TRUE) {
        echo "Competición eliminada correctamente";
    } else {
        echo "Error al eliminar competición: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    // Si 'competicion' no está definido en $_POST, mostrar un mensaje de error
    echo "Error: El campo 'competicion' no está definido en la solicitud POST.";
}

// Cerrar conexión
$conn->close();

// Redireccionar después de cerrar la conexión
header("Location: ../../paginas/eventos.php");
exit;
?>
