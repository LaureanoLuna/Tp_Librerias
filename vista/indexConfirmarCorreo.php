<?php
$Titulo = "Confirmar Correo";
include_once('../vista/estructura/cabecera.php');

require '../utiles/PHPMailer/Exception.php';
require '../utiles/PHPMailer/PHPMailer.php';
require '../utiles/PHPMailer/SMTP.php';

$datos = data_submitted();

<<<<<<< HEAD
if (!empty($datos) && empty($datos['accion'])) {
    $objABMAuth = new ABMAuth;
    $resp = $objABMAuth->registrarse($datos, $AUTH);
    if ($resp['alerta'] <> null) {
=======
    mailToken($resp['token'], $resp['selector'], $datos['email']);
{
>>>>>>> 0bf8f2d817ce5a4bbdac4c9c34133379c0049f28
?>
        <div class="container p-2">
            <?php echo $resp['alerta']; ?>
        </div>
        <?php
    } else {

        $correoConfirmado = mailToken($resp['token'], $resp['selector'], $datos['email']);

        if ($correoConfirmado) {
        ?>
            <div class="position-absolute top-20 start-50 translate-middle">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-envelope-open mx-2"></i>Revise su bandeja de entrada e ingrese los tokens
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php
        } else {
        ?>
            <div class="position-absolute top-20 start-50 translate-middle">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-envelope-open mx-2"></i> Algo salió mal D:
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php
        }

        ?>
        <div class="container p-4 mt-5 border border-3 rounded-2 bg-light" style="width: 350px;">
            <h5 class="text-center"><i class="fa-solid fa-user-check mx-2"></i>Confirmar Correo</h5>
            <hr>
            <!-- FORMULARIO INGRESAR TOKEN Y SELECTOR -->
            <form action="accionConfirmarCorreo.php" name="confirmar" id="confirmar" method="post" accept-charset="utf-8">
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
            <hr>
            <!-- FORMULARIO GENERAR NUEVO TOKEN Y SELECTOR -->
            <form action="accionGenerarToken.php" method="post" accept-charset="utf-8">
                <input type=hidden name="email" value="<?php echo $datos['email'] ?>">
                <button type="submit" class="btn btn-outline-warning">Generar Token</button>
            </form>
            <hr>
        <?php
    }
} elseif (!empty($datos['accion'])) {
        if ($datos['accion'] == true) {
        echo "
            <div class='position-absolute top-20 start-50 translate-middle'>
                <div class='alert alert-info alert-dismissible fade show' role='alert'>
                    <i class='fa-solid fa-envelope-open mx-2'></i> Se generaron nuevos tokens, revise su bandeja de entrada!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>
            ";
        } else {
            echo "
            <div class='position-absolute top-20 start-50 translate-middle'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <i class='fa-solid fa-mark mx-2'></i> No se pudieron generar los nuevos tokens!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>
            ";
        }
        ?>
        <div class="container p-4 mt-5 border border-3 rounded-2 bg-light" style="width: 350px;">
            <h5 class="text-center"><i class="fa-solid fa-user-check mx-2"></i>Confirmar Correo</h5>
            <hr>
            <!-- FORMULARIO INGRESAR TOKEN Y SELECTOR -->
            <form action="accionConfirmarCorreo.php" name="confirmar" id="confirmar" method="post" accept-charset="utf-8">
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
            <hr>
        <?php
    } else {
        ?>
        <div class="container p-4 mt-5 border border-3 rounded-2 bg-light" style="width: 350px;">
            <h5 class="text-center"><i class="fa-solid fa-user-check mx-2"></i>Confirmar Correo</h5>
            <hr>
            <!-- FORMULARIO INGRESAR TOKEN Y SELECTOR -->
            <form action="accionConfirmarCorreo.php" name="confirmar" id="confirmar" method="post" accept-charset="utf-8">
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
        <hr>

        <?php } ?>
        <form action="indexRegistro.php" method="post" accept-charset="utf-8">
            <button type="submit" class="btn btn-outline-info">Volver a Registro</button>
        </form>
        </div>

        <?php include_once('../vista/estructura/scripts.php'); ?>