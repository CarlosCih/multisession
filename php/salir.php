<?php
session_start();

// Verificar si se solicitó el cierre de sesión
if(isset($_POST['salir'])) {
    // Eliminar todas las variables de sesión
    $_SESSION = array();
    // Destruir la sesión
    session_destroy();
    
    // Eliminar la cookie de sesión (PHPSESSID)
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Redirigir al usuario a la página de inicio
    header("Location: ../index.html");
    exit();
}
?>
