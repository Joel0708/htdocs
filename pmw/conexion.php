<?php
$host = "localhost"; // o 127.0.0.1
$usuario = "root"; // tu usuario MySQL
$clave = ""; // tu contraseña MySQL
$base_de_datos = "pmw";

// Crear conexión
$conn = new mysqli($host, $usuario, $clave, $base_de_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// echo "Conexión exitosa";
?>
