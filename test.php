<?php
$contrasena = '1234';
// Función para cifrar la contraseña usando password_hash
function cifrarContrasena($contrasena)
{
    return password_hash($contrasena, PASSWORD_DEFAULT);
}

echo cifrarContrasena($contrasena);