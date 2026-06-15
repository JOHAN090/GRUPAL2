<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario']) && isset($_POST['password'])) {
    $usuario_ingresado = $_POST['usuario'];
    $password_ingresado = $_POST['password'];

    // Leer la base de datos de usuarios en JSON
    $archivo_usuarios = 'data/usuarios.json';
    if(file_exists($archivo_usuarios)){
        $usuarios = json_decode(file_get_contents($archivo_usuarios), true);
        
        // Verificar si existe el usuario y la contraseña coincide
        if (isset($usuarios[$usuario_ingresado]) && $usuarios[$usuario_ingresado]['password'] === $password_ingresado) {
            
            // Guardar datos en la sesión
            $_SESSION['usuario_id'] = $usuario_ingresado;
            $_SESSION['rol'] = $usuarios[$usuario_ingresado]['rol'];
            $_SESSION['nombre'] = $usuarios[$usuario_ingresado]['nombre'];
            
            header("Location: bandeja.php");
            exit;
        }
    }
    // Si falla, lo devolvemos con un error
    header("Location: index.php?error=1");
    exit;
}

header("Location: index.php");
exit;
?>