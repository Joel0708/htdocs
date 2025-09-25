<?php
include "conexion.php";
session_start();

// Verificar que estamos recibiendo datos del registro de vendedor
if(isset($_POST['nombre_tienda']) && isset($_POST['id_usuario'])){
    $id_usuario = intval($_POST['id_usuario']);
    $nombre_tienda = $conn->real_escape_string($_POST['nombre_tienda']);

    // 1️⃣ Crear tienda
    $sql_tienda = "INSERT INTO tiendas (id_usuario, nombre_tienda) VALUES ($id_usuario, '$nombre_tienda')";
    if($conn->query($sql_tienda)){
        $id_tienda = $conn->insert_id; // Obtenemos el ID de la tienda creada

        // 2️⃣ Crear las 4 páginas por defecto
        $paginas = [
            'inicio' => 'Inicio',
            'productos' => 'Productos',
            'productos2' => 'Productos 2',
            'nosotros_contacto' => 'Nosotros / Contacto'
        ];

        $ids_paginas = [];
        foreach($paginas as $slug => $nombre){
            $sql_pg = "INSERT INTO paginas (id_tienda, slug, nombre) VALUES ($id_tienda, '$slug', '$nombre')";
            if($conn->query($sql_pg)){
                $ids_paginas[$slug] = $conn->insert_id;
            }
        }

        // 3️⃣ Crear secciones básicas por cada página
        foreach($ids_paginas as $slug => $id_pagina){
            $secciones = [
                'header' => "<header><h1>Bienvenido a $nombre_tienda</h1></header>",
                'banner' => "<div class='banner'><img src='img/banner_default.png'></div>",
                'principal' => "<p>Contenido principal de la página $slug...</p>",
                'footer' => "<footer>© 2025 $nombre_tienda</footer>"
            ];

            foreach($secciones as $sec => $html){
                $html_esc = $conn->real_escape_string($html);
                $sql_sec = "INSERT INTO contenido (id_pagina, seccion, html) VALUES ($id_pagina, '$sec', '$html_esc')";
                $conn->query($sql_sec);
            }
        }

        echo "Tienda y páginas creadas correctamente!";
    } else {
        echo "Error al crear tienda: ".$conn->error;
    }
} else {
    echo "Faltan datos";
}
?>
