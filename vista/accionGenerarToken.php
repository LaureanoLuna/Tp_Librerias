<?php
include_once('../vista/estructura/cabecera.php');
require '../utiles/PHPMailer/Exception.php';
require '../utiles/PHPMailer/PHPMailer.php';
require '../utiles/PHPMailer/SMTP.php';

$datos = data_submitted();
$objABMAuth = new ABMAuth;
$resp = $objABMAuth->generarToken($datos, $AUTH);

if ($resp['alerta'] <> null) {
?>
    <div class="container p-2">
        <?php echo $resp['alerta']; ?>
    </div>
<?php
} else {

    if(mailToken($resp['token'],$resp['selector'],$datos['email'])){
        echo "<script> window.location.href='indexConfirmarCorreo.php?accion=true'</script>";
    }else{
        echo "<script> window.location.href='indexRegistro.php?accion=false'</script>";
    }
}
?>
