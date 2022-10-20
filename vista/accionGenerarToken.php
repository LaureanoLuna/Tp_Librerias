<?php
$Titulo = "Generar Token";
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

    if(mailToken($resp['token'],$resp['selector'],$datos)){
        echo "<script> window.location.href='./ConfirmatToken.php'</script>";
    }else{
        echo "<script> window.location.href='./indexRegistro.php?accion=false'</script>";
    }
    
echo $resp['selector']."<br>";
echo $resp['token']."<br>";
echo $datos['email']."<br>";
?>

    <div class="position-absolute top-50 start-50 translate-middle border border-3 rounded-2 p-4 bg-light">
        <!-- FORMULARIO GENERAR TOKEN Y SELECTOR -->
        <form action="accionConfirmarCorreo.php" name="generar" id="generar" method="post" accept-charset="utf-8">
            <h4 class="text-center"><i class="fa-solid fa-envelope-open"></i></h4>
            <h5>Revise su bandeja de entrada e ingrese los tokens</h5>
            <hr>
            <div class="form-group mb-3">
                <label for="selector" class="form-label">Selector: </label>
                <input type="text" class="form-control" id="selector" name="selector">
            </div>
            <div class="form-group mb-3">
                <label for="token" class="form-label">Token: </label>
                <input type="text" class="form-control" id="token" name="token">
            </div>
            <button type="submit" class="btn btn-outline-primary">Confirmar Correo</button>
        </form>

    <?php } ?>

    <div class="pt-3" style="width: 300px;">
        <form action="indexRegistro.php" method="post" accept-charset="utf-8">
            <button type="submit" class="btn btn-outline-info">Volver a Registro</button>
        </form>
    </div>

    </div>
    
<?php
include_once('../vista/estructura/scripts.php');
?>