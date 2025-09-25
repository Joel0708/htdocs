<?php
include "conexion.php";
session_start();
$msg = "";

// LOGIN
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $res = $conn->query($sql);

    if($res->num_rows == 1){
        $user = $res->fetch_assoc();
        if(password_verify($pass, $user['password'])){
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];

            // Redirigir según rol
            if($user['rol'] == 'admin'){
                header("Location: admin_panel.php");
            } elseif($user['rol'] == 'vendedor'){
                header("Location: vendedor_panel.php");
            } else {
                header("Location: cliente_panel.php");
            }
            exit;
        } else {
            $msg = "Contraseña incorrecta";
        }
    } else {
        $msg = "Email no registrado";
    }
}

// REGISTRO CLIENTE
if(isset($_POST['register'])){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = 'cliente';

    $sql_check = "SELECT * FROM usuarios WHERE email='$email'";
    $res_check = $conn->query($sql_check);

    if($res_check->num_rows == 0){
        $sql = "INSERT INTO usuarios (nombre,email,password,rol) VALUES ('$nombre','$email','$password','$rol')";
        if($conn->query($sql)){
            $msg = "Cliente registrado correctamente. Ahora podés iniciar sesión.";
        } else {
            $msg = "Error al registrar: " . $conn->error;
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
<title>Login PMW</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1>Iniciar Sesión / Registro Cliente</h1>
<?php if($msg) echo '<div class="alert alert-info">'.$msg.'</div>'; ?>

<!-- LOGIN -->
<h3>Login</h3>
<form method="POST">
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
</form>
<hr>

<!-- REGISTRO CLIENTE -->
<h3>Registro Cliente</h3>
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
    <button type="submit" name="register" class="btn btn-success">Registrarse como Cliente</button>
</form>
<hr>

<!-- BOTÓN REGISTRO VENDEDOR -->
<div class="mb-3">
    <p>¿Querés crear tu tienda?</p>
    <a href="registro_vendedor.php" class="btn btn-warning">Registrar Vendedor / Crear Tienda</a>
</div>
</div>
</body>
</html>
