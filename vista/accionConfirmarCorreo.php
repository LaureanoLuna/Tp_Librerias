<?php
$Titulo = "Confirmar Correo";
include_once('../vista/estructura/cabecera.php');

$datos = data_submitted();
$objABMAuth = new ABMAuth;
$resp = $objABMAuth->confirmarCorreo($datos, $AUTH);
?>
<div class="container p-2">

    <script>
        window.location.href = './indexSeguro.php';
    </script>

</div>

<div class="text-center" style="width: 300px;">
    <form action="indexInicio.php" method="post" accept-charset="utf-8">
        <button type="submit" class="btn btn-outline-info">Volver a Log In</button>
    </form>
</div>
