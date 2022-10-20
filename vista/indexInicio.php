<?php
$Titulo = "Inicio";
include_once('../vista/estructura/cabecera.php');
?>
<div class="position-absolute top-50 start-50 translate-middle border border-3 rounded-2 p-4 bg-light">
    <div style="width: 300px;">
        <!-- INICIO FORMULARIO INICIAR SESIÓN -->
        <form action="accionLogueo.php" method="post" name="logeo" id="logeo" accept-charset="utf-8" class="mb-3">
            <div class="form-group mb-3">
                <label for="username" class="form-label">Nombre de Usuario: </label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Contraseña: </label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <input name="remember" value="0" hidden>
            <button type="submit" class="btn btn-outline-primary">Iniciar Sesión</button>
        </form>
        <!-- FIN FORMULARIO INICIAR SESIÓN -->
        <div class="p3">
            No est&aacute; registrado?
            <a href="indexRegistro.php">Registrarse</a>
        </div>
    </div>
</div>
<?php
include_once('../vista/estructura/scripts.php');
?>