<?php
$Titulo = "Registro";
include_once('../vista/estructura/cabecera.php');

$datos = data_submitted();


?>
<div class="position-absolute top-50 start-50 translate-middle border border-3 rounded-2 p-4 bg-light">
    <!-- INICIO FORMULARIO DE REGISTRO -->
    <div style="width: 300px;">
        <?php if(!empty($datos['mensaje'])){
            echo " <div class='container p-2'>".$resp['alerta']."</div>"; }
        ?>
        <form action="indexConfirmarCorreo.php" name="registro" id="registro"  method="post" accept-charset="utf-8">
            <div class="form-group mb-3">
                <label for="email" class="form-label">Correo: </label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group mb-3">
                <label for="username" class="form-label">Nombre de Usuario: </label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Contraseña: </label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <input name="require_verification" value="1" hidden>
            <input name="require_unique_username" value="1" hidden>
            <button type="submit" class="btn btn-outline-success">Registrarse</button>
        </form>
    </div>
    <!-- FIN FORMULARIO DE REGISTRO -->
    <div class="pt-3" style="width: 300px;">
        <form action="indexInicio.php" method="post" accept-charset="utf-8">
            <button type="submit" class="btn btn-outline-info">Volver a Log In</button>
        </form>
    </div>
</div>
<?php
include_once('../vista/estructura/scripts.php');
?>