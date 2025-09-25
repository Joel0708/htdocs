<?php
include "conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PMW - Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Productos Disponibles</h1>
    <div class="row">
        <?php
        $sql = "SELECT * FROM productos";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="'.$row['imagen'].'" class="card-img-top" alt="'.$row['nombre'].'">
                            <div class="card-body">
                                <h5 class="card-title">'.$row['nombre'].'</h5>
                                <p class="card-text">'.$row['descripcion'].'</p>
                                <p class="card-text"><strong>Precio:</strong> $'.$row['precio'].'</p>
                                <p class="card-text"><strong>Stock:</strong> '.$row['stock'].'</p>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo "<p>No hay productos disponibles</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
