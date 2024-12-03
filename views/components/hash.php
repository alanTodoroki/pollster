<?php
$contrasenia = '1234'; // La contraseña que deseas insertar
$hash = password_hash($contrasenia, PASSWORD_BCRYPT);
echo $hash;
