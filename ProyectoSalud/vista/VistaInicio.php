<?php
// VistaInicio.php
function mostrarInicio($datos) {
    ?>
    <html>
    <head>
        <title>Proyecto Salud - MVC</title>
        <link rel="stylesheet" href="/css/styles.css">
    </head>
    <body>
        <h1><?= $datos['mensaje'] ?></h1>
        <p>Fecha: <?= $datos['fecha'] ?></p>
    </body>
    </html>
    <?php
}
