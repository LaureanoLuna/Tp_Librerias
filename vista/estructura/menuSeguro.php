<!-- NAVBAR SEGURO INICIO -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border rounded">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="../vista/indexSeguro.php"><i class="fa-solid fa-user"></i> <?php echo $AUTH->getUsername() ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../vista/Consulta.php"><i class="fa-solid fa-clipboard-question"></i> Consultas</a>
                </li>
            </ul>
        </div>
        <form class="justify-content-start" action="accionCerrarSesion.php" method="post" accept-charset="utf-8">
            <button type="submit" class="btn btn-outline-danger me-2"><i class="fa-solid fa-door-open"></i> Cerrar Sesión</button>
        </form>
    </div>
</nav>
<!-- NAVBAR SEGURO FIN -->