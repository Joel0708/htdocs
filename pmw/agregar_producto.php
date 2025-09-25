<?php
include "conexion.php";

// Procesar formulario
if(isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Subida de imagen
    $imagen = $_FILES['imagen']['name'];
    $target = "img/".basename($imagen);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target)) {
        $msg = "Imagen subida correctamente";
    } else {
        $msg = "Error al subir la imagen";
    }

    // Insertar producto en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen)
            VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$target')";

    if($conn->query($sql) === TRUE) {
        $msg .= " | Producto agregado correctamente!";
    } else {
        $msg .= " | Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto - PMW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Agregar Producto</h1>
    <?php if(isset($msg)) echo '<div class="alert alert-info">'.$msg.'</div>'; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nombre del producto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Imagen</label>
            <input type="file" name="imagen" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Agregar Producto</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Volver al listado</a>
</div>
</body>
</html>
