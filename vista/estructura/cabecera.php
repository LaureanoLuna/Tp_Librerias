<!doctype html>
<html lang="es">
<!-- INICIO CABECERA -->
<head>
    <title><?php echo $Titulo ?></title>
    <meta charset="utf-8">
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="../utiles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../utiles/bootstrap/css/bootstrapValidator.min.css">
    <!-- CSS PROPIO -->
    <link rel="stylesheet" href="./css/general.css">
    <!-- Iconos Libreria -->
    <script src="../utiles/Iconos/FontAwesomeKit.js"></script>
    <!-- ICON -->
    <link rel="icon" type="image/x-icon" href="./img/icon.ico">

    <?php 
        include_once("../configuracion.php");
    ?>

</head>

<body class="container-fluid ">
    <?php 
    if($AUTH->isLoggedIn()){
        include_once("menuSeguro.php"); 
    }else{
        include_once("menu.php"); 
    }
 ?>
    <!-- FIN CABECERA -->