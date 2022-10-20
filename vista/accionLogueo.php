<?php
include_once('../vista/estructura/cabecera.php');
require_once('../utiles/funciones.php');
$Titulo = "Logueo";
$datos=data_submitted();
$objAMBAuth= new ABMAuth;
$resp=$objAMBAuth->loguearse($datos,$AUTH);
?>
<div class="container p-2">
    <?php echo $resp; ?>
</div>

<div class="text-center" style="width: 300px;">
    <form action="indexInicio.php" method="post" accept-charset="utf-8">
        <button type="submit" class="btn btn-info">Volver</button>
    </form>
</div>