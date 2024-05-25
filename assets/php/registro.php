<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene los valores del formulario
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];

    // Conexión a la base de datos
    $servidor = "localhost";
    $usuario_bd = "root";
    $contraseña_bd = "";
    $nombre_bd = "usuarios";

    $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Prepara la consulta SQL para insertar el nuevo usuario en la tabla
    $sql = "INSERT INTO nuevos (usuario, contraseña) VALUES ('$usuario', '$contraseña')";

    // Ejecuta la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        echo "Registro exitoso";
        // Espera 2 segundos antes de redirigir al usuario
        header("refresh:2; url=../../paginas/login.html");
        exit();
    } else {
        echo "Error al registrar usuario: " . $conexion->error;
    }

    // Cierra la conexión
    $conexion->close();
}
?>
