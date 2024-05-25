<?php
// Verifica si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_ingresado = $_POST["usuario"];
    $contraseña_ingresada = $_POST["contraseña"];

    $servidor = "localhost";
    $usuario_bd = "root";
    $contraseña_bd = "";
    $nombre_bd = "usuarios";

    $conexion = new mysqli($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Prepara la consulta SQL para obtener el usuario, contraseña y rol de la base de datos
    $sql = "SELECT usuario, contraseña, rol FROM nuevos WHERE usuario = '$usuario_ingresado'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        // Usuario encontrado
        $fila = $resultado->fetch_assoc();
        $usuario_bd = $fila["usuario"];
        $contraseña_bd = $fila["contraseña"];
        $rol_usuario = $fila["rol"];

        if ($contraseña_ingresada === $contraseña_bd) {
            // Inicio de sesión exitoso
            if ($rol_usuario === "administrador") {
                // Si el usuario es administrador, redirige a la página de administrador
                header("Location:../../index-registrado.html");
                exit();
            } else {
                // Si el usuario es normal, redirige a la página de inicio de sesión
                header("Location:../../index-registrado-usuario.html");
                exit();
            }
        } else {
            // Contraseña incorrecta
            // Redirige al usuario de vuelta a la página de inicio de sesión con un mensaje de error
            header("Location: ../../paginas/error-login.html");
            exit();
        }
    } else {
        // Usuario no encontrado
        // Redirige al usuario de vuelta a la página de inicio de sesión con un mensaje de error
        header("Location: ../../paginas/error-login.html");
        exit();
    }

    // Cierra la conexión
    $conexion->close();
}
?>
