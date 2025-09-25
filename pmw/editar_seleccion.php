<?php
session_start();
require 'conexion.php'; // Asegúrate que acá está tu conexión a la BD

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar que vienen los datos del formulario
if (isset($_POST['seccion']) && isset($_POST['contenido'])) {
    $seccion = $_POST['seccion'];
    $contenido = $_POST['contenido'];

    // Sanitizar el contenido (permitir HTML pero evitar inyección peligrosa)
    $contenido = mysqli_real_escape_string($conexion, $contenido);

    // Armar el SQL dinámico dependiendo de la sección
    $columna = "";
    switch ($seccion) {
        case 'descripcion':
            $columna = "descripcion";
            break;
        case 'politicas':
            $columna = "politicas";
            break;
        case 'faq':
            $columna = "faq";
            break;
        default:
            die("Sección inválida.");
    }

    // Actualizar el contenido en la tabla tiendas
    $sql = "UPDATE tiendas SET $columna = '$contenido' WHERE id_usuario = '$id_usuario'";
    if (mysqli_query($conexion, $sql)) {
        $_SESSION['mensaje'] = "✅ Sección actualizada correctamente.";
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar: " . mysqli_error($conexion);
    }
}

// Redirigir de vuelta al panel del vendedor
header("Location: panel_vendedor.php");
exit();
?>
