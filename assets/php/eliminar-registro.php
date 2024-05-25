<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si la clave única está presente en la solicitud
    if (isset($_POST['clave_unica'])) {
        // Obtener la clave única del formulario
        $clave_unica = $_POST['clave_unica'];

        $servidor = "localhost";
        $usuario = "root";
        $pass = "";
        $bd = "pruebas";

        $conexion = mysqli_connect($servidor, $usuario, $pass, $bd);

        if (!$conexion) {
            $_SESSION["mensaje"] = "No se puede conectar con el servidor";
        } else {
            // Preparar la consulta SQL para eliminar el registro
            $query = "DELETE FROM participantes WHERE CONCAT(nombre, '_', apellidos) = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("s", $clave_unica);

            if ($stmt->execute()) {
                $_SESSION["mensaje"] = "Registro eliminado correctamente";
            } else {
                $_SESSION["mensaje"] = "Error al eliminar el registro: " . $conexion->error;
            }

            // Cerrar la conexión y redirigir a la URL deseada
            $stmt->close();
            $conexion->close();
            // Redireccionar a la URL deseada con mensaje de éxito
            header("Location: {$_SERVER['HTTP_REFERER']}?mensaje=Registro eliminado correctamente");
            exit();
        }
    } else {
        $_SESSION["mensaje"] = "Clave única no proporcionada";
    }
}

// Redirigir a la página anterior si algo falla
header("Location: {$_SERVER['HTTP_REFERER']}?mensaje=Error al eliminar el registro");
exit();
?>


</body>
</html>
