<?php
include "conexion.php";
session_start();

// Verificar si está logueado y es vendedor
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'vendedor'){
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener la tienda del vendedor
$sql_tienda = "SELECT * FROM tiendas WHERE id_usuario = $id_usuario";
$res_tienda = $conn->query($sql_tienda);
$tienda = $res_tienda->fetch_assoc();
$id_tienda = $tienda['id'];

// Mensajes
$msg = "";

// ----------------------- AGREGAR PRODUCTO -----------------------
if(isset($_POST['add_producto'])){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];

    // Subida de imagen
    $imagen = "";
    if(isset($_FILES['imagen']) && $_FILES['imagen']['name'] != ""){
        $imagen = "img/".$_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'],$imagen);
    }

    $sql = "INSERT INTO productos (id_tienda,id_categoria,nombre_producto,descripcion,precio,stock,imagen)
            VALUES ($id_tienda,$categoria,'$nombre','$descripcion','$precio','$stock','$imagen')";
    if($conn->query($sql)){
        $msg = "Producto agregado correctamente";
    } else {
        $msg = "Error: ".$conn->error;
    }
}

// ----------------------- OBTENER CATEGORÍAS -----------------------
$sql_cats = "SELECT * FROM categorias WHERE id_tienda=$id_tienda";
$res_cats = $conn->query($sql_cats);

// ----------------------- OBTENER PÁGINAS -----------------------
$sql_paginas = "SELECT * FROM paginas WHERE id_tienda=$id_tienda";
$res_paginas = $conn->query($sql_paginas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Vendedor PMW</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.tiny.cloud/1/gksx3v2oqnr2555sa8zf0jkq1cm435jny2w6m299z2l9qpju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '.editor',
    height: 200
});
</script>
</head>
<body>
<div class="container mt-4">
<h1>Panel del Vendedor: <?php echo $tienda['nombre_tienda']; ?></h1>
<?php if($msg) echo '<div class="alert alert-info">'.$msg.'</div>'; ?>

<hr>
<h3>Agregar Producto</h3>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nombre del producto</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control" required></textarea>
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
        <label>Categoría</label>
        <select name="categoria" class="form-control" required>
            <?php while($cat = $res_cats->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nombre_categoria']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Imagen</label>
        <input type="file" name="imagen" class="form-control">
    </div>
    <button type="submit" name="add_producto" class="btn btn-success">Agregar Producto</button>
</form>

<hr>
<h3>Editar Páginas</h3>
<?php while($pag = $res_paginas->fetch_assoc()): ?>
<div class="card mb-3">
    <div class="card-header">
        Página: <?php echo $pag['slug']; ?>
    </div>
    <div class="card-body">
        <?php
        $sql_sec = "SELECT * FROM contenido WHERE id_pagina=".$pag['id'];
        $res_sec = $conn->query($sql_sec);
        while($sec = $res_sec->fetch_assoc()):
        ?>
        <form method="POST" action="editar_seccion.php">
            <h5>Sección: <?php echo $sec['seccion']; ?></h5>
            <textarea name="html" class="editor"><?php echo htmlspecialchars($sec['html']); ?></textarea>
            <input type="hidden" name="id_contenido" value="<?php echo $sec['id']; ?>">
            <button type="submit" class="btn btn-primary mt-2">Guardar</button>
        </form>
        <hr>
        <?php endwhile; ?>
    </div>
</div>
<?php endwhile; ?>

</div>
</body>
</html>
