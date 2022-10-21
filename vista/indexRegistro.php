<?php
$Titulo = "Registro";
include_once('../vista/estructura/cabecera.php');

$datos = data_submitted();


?>
<div class="container p-4 mt-5 border border-3 rounded-2 bg-light" style="width: 350px;">
    <?php if (!empty($datos['mensaje'])) {
        echo " <div class='container p-2'>" . $resp['alerta'] . "</div>";
    }
    ?>
    <h5 class="text-center"><i class="fa-solid fa-user-plus mx-2"></i>Confirmar Correo</h5>
    <hr>
    <!-- INICIO FORMULARIO DE REGISTRO -->
    <form action="indexConfirmarCorreo.php" name="registro" id="registro" method="post" accept-charset="utf-8">
        <div class="form-group mb-3">
            <label for="email" class="form-label">Correo: </label>
            <input type="email" class="form-control" id="email" name="email" autocomplete="off">
        </div>
        <div class="form-group mb-3">
            <label for="username" class="form-label">Nombre de Usuario: </label>
            <input type="text" class="form-control" id="username" name="username" autocomplete="off">
        </div>
        <div class="form-group mb-3">
            <label for="password" class="form-label">Contraseña: </label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
        </div>
        <input name="require_verification" value="1" hidden>
        <input name="require_unique_username" value="1" hidden>
        <button type="submit" class="btn btn-outline-success">Registrarse</button>
    </form>
    <hr>
    <!-- FIN FORMULARIO DE REGISTRO -->
    <form action="indexInicio.php" method="post" accept-charset="utf-8">
        <button type="submit" class="btn btn-outline-info">Volver a Log In</button>
    </form>
</div>
<?php
include_once('../vista/estructura/scripts.php');
?>