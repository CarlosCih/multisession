<?php
session_start();
include "conexion.php";

$conn = Conectar();

// Función para cifrar la contraseña usando password_hash
function cifrarContrasena($contrasena)
{
    return password_hash($contrasena, PASSWORD_DEFAULT);
}

// Función para obtener el nombre de la cookie de sesión según el tipo de ventana
function obtenerNombreCookie() {
    if (isset($_SERVER['HTTP_INCOGNITO'])) {
        return 'sesion_incognito'; // Nombre de la cookie para sesiones en modo incógnito
    } else {
        return 'sesion_normal'; // Nombre de la cookie para sesiones normales
    }
}

function register($conn) {
    if(isset($_POST['registro'])) {
        $nombres = $_POST['nombres']; 
        $apellidos = $_POST['apellidos']; 
        $usuario = $_POST['usuario']; 
        $password = $_POST['password']; 
        $contrasenaCifrada = cifrarContrasena($password); // Cifra la contraseña

        // Verificar si el usuario ya existe
        $sql_check = "SELECT * FROM usuarios WHERE usuario=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $usuario);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "<script>alert('El usuario ya está registrado.');window.location.reload()</script>";
        } else {
            // Insertar el nuevo usuario de manera segura con consulta preparada
            $sql = "INSERT INTO usuarios (nombres, apellidos, usuario, contrasena) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nombres, $apellidos, $usuario, $contrasenaCifrada); // Usar la contraseña cifrada
            if ($stmt->execute()) {
                echo "<script>alert('Usuario registrado correctamente.');window.location='../index.html'</script>";
                exit(); // Termina el script después de la redirección
            } else {
                echo "<script>alert('Error al registrar usuario: " . $stmt->error . "');</script>";
                echo "<script>window.location='../index.html'</script>";
                exit(); // Termina el script después de la redirección
            }
        }
    }
}

function login($conn) {
    if(isset($_POST['login'])) {
        $usuario = $_POST['usuario']; 
        $contrasena = $_POST['contrasena']; 

        $sql = "SELECT * FROM usuarios WHERE usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($contrasena, $row['contrasena'])) {
                // Regenerar el identificador de sesión para mayor seguridad
                session_regenerate_id(true);

                // Establecer la cookie de sesión con el nombre correspondiente
                setcookie(obtenerNombreCookie(), session_id(), 0, '/');

                session_start(); // Inicia la sesión
                $_SESSION['usuario'] = $usuario; // Guarda el nombre de usuario en la sesión
                $_SESSION['usuario_id'] = $row['id']; // Guarda el ID de usuario en la sesión

                header('Location: dashboard.php'); // Redirecciona al usuario a la página de dashboard
                exit(); // Termina el script después de la redirección
            } else {
                echo "<script>alert('Contraseña incorrecta');window.location='../index.html'</script>";
                exit(); // Termina el script después de la redirección
            }
        } else {
            echo "<script>alert('Usuario no encontrado');window.location='../index.html'</script>";
            exit(); // Termina el script después de la redirección
        }
    }
}

$registro_resultado = register($conn);

if(isset($_POST['login'])) {
    $usuario = $_POST['usuario']; 
    $contrasena = $_POST['contrasena']; 
    login($conn);
}
?>
