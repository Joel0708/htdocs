<?php
include "conexion.php";

if(isset($_POST['submit'])){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nombre_tienda = $_POST['tienda'];
    $rol = 'vendedor';

    // Verificar si el email ya existe
    $sql_check = "SELECT * FROM usuarios WHERE email='$email'";
    $res = $conn->query($sql_check);

    if($res->num_rows == 0){
        // Crear usuario
        $sql = "INSERT INTO usuarios (nombre,email,password,rol) VALUES ('$nombre','$email','$password','$rol')";
        if($conn->query($sql) === TRUE){
            $id_usuario = $conn->insert_id;

            // Crear tienda
            $sql_tienda = "INSERT INTO tiendas (id_usuario,nombre_tienda) VALUES ($id_usuario,'$nombre_tienda')";
            $conn->query($sql_tienda);
            $id_tienda = $conn->insert_id;

            // Crear páginas base
            $paginas_base = [
                ['slug'=>'inicio', 'titulo'=>'Bienvenido a tu tienda'],
                ['slug'=>'productos', 'titulo'=>'Nuestros productos'],
                ['slug'=>'productos2', 'titulo'=>'Más productos'],
                ['slug'=>'nosotros_contacto', 'titulo'=>'Nosotros y Contacto']
            ];

            foreach($paginas_base as $p){
                $sql_pag = "INSERT INTO paginas (id_tienda, slug, titulo) VALUES ($id_tienda,'{$p['slug']}','{$p['titulo']}')";
                $conn->query($sql_pag);
                $id_pagina = $conn->insert_id;

                // Crear secciones iniciales
                $secciones = ['header','banner','principal','footer'];
                foreach($secciones as $s){
                    $sql_cont = "INSERT INTO contenido (id_pagina,seccion,html) VALUES ($id_pagina,'$s','<p>Texto de ejemplo</p>')";
                    $conn->query($sql_cont);
                }
            }

            $msg = "Vendedor y tienda creados correctamente. Ya podés iniciar sesión.";
        } else {
            $msg = "Error al crear el usuario: " . $conn->error;
        }
    } else {
        $msg = "El email ya está registrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro Vendedor PMW</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1>Registro de Vendedor</h1>
<?php if(isset($msg)) echo '<div class="alert alert-info">'.$msg.'</div>'; ?>
<form method="POST">
    <div class="mb-3">
        <label>Nombre completo</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nombre de tu tienda</label>
        <input type="text" name="tienda" class="form-control" required>
    </div>
    <button type="submit" name="submit" class="btn btn-warning">Registrarse como vendedor</button>
</form>
<hr>
<a href="login.php" class="btn btn-secondary">Volver al login</a>
</div>
</body>
</html>
