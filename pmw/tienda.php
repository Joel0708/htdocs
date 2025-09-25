<?php
include "conexion.php";
session_start();

// Verificar que se pase el id de la tienda en la URL
if(!isset($_GET['id_tienda'])){
    die("Tienda no especificada");
}

$id_tienda = intval($_GET['id_tienda']);

// Página a mostrar, por defecto 'inicio'
$slug = isset($_GET['pagina']) ? $_GET['pagina'] : 'inicio';

// Obtener información de la tienda
$sql_tienda = "SELECT * FROM tiendas WHERE id=$id_tienda";
$res_tienda = $conn->query($sql_tienda);
if($res_tienda->num_rows == 0){
    die("Tienda no encontrada");
}
$tienda = $res_tienda->fetch_assoc();

// Obtener la página
$sql_pagina = "SELECT * FROM paginas WHERE id_tienda=$id_tienda AND slug='$slug'";
$res_pagina = $conn->query($sql_pagina);
if($res_pagina->num_rows == 0){
    die("Página no encontrada");
}
$pagina = $res_pagina->fetch_assoc();

// Obtener contenido de la página
$sql_contenido = "SELECT * FROM contenido WHERE id_pagina=".$pagina['id'];
$res_contenido = $conn->query($sql_contenido);
$secciones = [];
while($row = $res_contenido->fetch_assoc()){
    $secciones[$row['seccion']] = $row['html'];
}

// Obtener productos de la tienda
$sql_prod = "SELECT * FROM productos WHERE id_tienda=$id_tienda";
$res_prod = $conn->query($sql_prod);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?php echo $tienda['nombre_tienda']; ?> - <?php echo ucfirst($slug); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<?php if(isset($secciones['header'])) echo $secciones['header']; ?>

<!-- BARRA DE NAVEGACIÓN -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="tienda.php?id_tienda=<?php echo $id_tienda; ?>&pagina=inicio"><?php echo $tienda['nombre_tienda']; ?></a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="tienda.php?id_tienda=<?php echo $id_tienda; ?>&pagina=inicio">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="tienda.php?id_tienda=<?php echo $id_tienda; ?>&pagina=productos">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="tienda.php?id_tienda=<?php echo $id_tienda; ?>&pagina=productos2">Productos 2</a></li>
        <li class="nav-item"><a class="nav-link" href="tienda.php?id_tienda=<?php echo $id_tienda; ?>&pagina=nosotros_contacto">Nosotros / Contacto</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- BANNER -->
<?php if(isset($secciones['banner'])) echo $secciones['banner']; ?>

<!-- CONTENIDO PRINCIPAL -->
<div class="container mt-4">
<?php
if($slug == 'productos' || $slug == 'productos2'){
    echo "<div class='row'>";
    while($prod = $res_prod->fetch_assoc()){
        echo "<div class='col-md-4 mb-3'>";
        echo "<div class='card'>";
        if($prod['imagen'] != "") echo "<img src='".$prod['imagen']."' class='card-img-top'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>".$prod['nombre_producto']."</h5>";
        echo "<p class='card-text'>".$prod['descripcion']."</p>";
        echo "<p class='card-text'><strong>Precio:</strong> $".$prod['precio']."</p>";
        echo "</div></div></div>";
    }
    echo "</div>";
} else {
    if(isset($secciones['principal'])) echo $secciones['principal'];
}
?>
</div>

<!-- FOOTER -->
<?php if(isset($secciones['footer'])) echo $secciones['footer']; ?>

</body>
</html>
