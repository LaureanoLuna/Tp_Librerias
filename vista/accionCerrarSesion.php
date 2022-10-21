<?php
include_once('../vista/estructura/cabecera.php');
require_once('../utiles/funcionesAuth.php');
require_once('../utiles/funciones.php');
$abmAuth = new ABMAuth();
$resp = $abmAuth->cerrarSesion($AUTH);
?>
<div class="container p-2">
    <?php echo $resp; ?>
</div>