<?php
include_once('../vista/estructura/cabecera.php');
require_once('../utiles/funcionesAuth.php');
require_once('../utiles/funciones.php');
$Titulo = "Cerrar Sesión";
$resp=cerrarSesion($AUTH);
?>
<div class="container p-2">
    <?php echo $resp; ?>
</div>