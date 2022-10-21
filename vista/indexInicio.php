<?php
$Titulo = "Inicio";
include_once('../vista/estructura/cabecera.php');
?>
<div class="container p-4 mt-5 border border-3 rounded-2 bg-light" style="width: 350px;">
    <h5 class="text-center"><i class="fa-solid fa-person-arrow-up-from-line mx-2"></i>Log In</h5>
    <hr>
    <!-- INICIO FORMULARIO INICIAR SESIÓN -->
    <form action="accionLogueo.php" method="post" name="logeo" id="logeo" accept-charset="utf-8" class="mb-3">
        <div class="form-group mb-3">
            <label for="username" class="form-label">Nombre de Usuario: </label>
            <input type="text" class="form-control" id="username" name="username" autocomplete="off">
        </div>
        <div class="form-group mb-3">
            <label for="password" class="form-label">Contraseña: </label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
        </div>
        <input name="remember" value="0" hidden>
        <button type="submit" class="btn btn-outline-primary">Iniciar Sesión</button>
    </form>
    <!-- FIN FORMULARIO INICIAR SESIÓN -->
    <div class="p3">
        No est&aacute; registrado?
        <a href="indexRegistro.php">Registrarse</a>
    </div>
    <div class="p3">
        Ya tenés tus tokens?
        <a href="indexConfirmarCorreo.php">Confirmar Correo</a>
    </div>
</div>
<?php
include_once('../vista/estructura/scripts.php');
?>