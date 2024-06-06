<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "competiciones";

    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $modalidad = $_POST['modalidad'];
    $competicion_nombre = $_POST['competicion'];
    $categoria = $_POST['categoria'];
    $distancia = $_POST['distancia'];
    $fecha = $_POST['fecha'];
    $enlace = $_POST['enlace'];

    // Insertar datos en la base de datos
    $sql = "INSERT INTO listado (Modalidad, Competicion, Categoria, Distancia, Fecha, Enlace) VALUES ('$modalidad', '$competicion_nombre', '$categoria', '$distancia', '$fecha', '$enlace')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../paginas/eventos-copy.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
