<?php
session_start();
// Verificar si el usuario ha iniciado sesión
if(!isset($_SESSION['usuario_id'])){
    // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio
    header('Location: ../index.html');
    exit(); // Finalizar el script para evitar ejecución adicional
}

// Regenerar el identificador de sesión para mayor seguridad
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="form">
        <div id="login">
            <h1>Bienvenid@</h1>
            <h1><?php echo $_SESSION['usuario']; ?></h1>

            <!-- Mostrar más información del usuario si es necesario -->
            <p>Tu ID de usuario es: <?php echo $_SESSION['usuario_id']; ?></p>

            <form action="salir.php" method="post">
                <button class="button button-block" name="salir">Salir</button>
            </form>

        </div>
    </div>
</body>

</html>
