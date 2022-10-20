<?php
$Titulo = "Inicio Seguro";
include_once('../vista/estructura/cabecera.php');
if ($AUTH->isLoggedIn()) { // INICIO ESTA LOGEADO
?>
    <div class='container p-4 text-center'>
        <h3><i class="fa-solid fa-house-user"></i> Bienvenido a su Inicio</h3>
    </div>
    <!-- INICIO RELLENO -->
    <div class="container p-2">
        <div class="row">
            <div class="col-sm-6">
                <div class="card" aria-hidden="true">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder col-6"></span>
                        </h5>
                        <p class="card-text placeholder-glow">
                            <span class="placeholder col-7"></span>
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-6"></span>
                            <span class="placeholder col-8"></span>
                        </p>
                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card" aria-hidden="true">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder col-6"></span>
                        </h5>
                        <p class="card-text placeholder-glow">
                            <span class="placeholder col-7"></span>
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-6"></span>
                            <span class="placeholder col-8"></span>
                        </p>
                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                    </div>
                </div>
            </div>
        </div>
        <p class="placeholder-glow pt-4"><span class="placeholder col-12"></span></p>
        <p class="placeholder-glow"><span class="placeholder col-8"></span></p>
        <p class="placeholder-glow"><span class="placeholder col-10"></span></p>
    </div>
    <!-- FIN RELLENO -->
<?php
    include_once('../vista/estructura/pie.php'); // FIN ESTA LOGEADO
} else {  // INICIO NO ESTA LOGEADO

?>
    <div class="container p-2">
        <div class="alert alert-danger" role="alert">
            <i class="fa-solid fa-xmark mx-2"></i> No se ha iniciado sesión - <a href='indexInicio.php'> Ingrese aquí </a>
        </div>
    </div>
<?php }  // FIN NO ESTA LOGEADO
?>