<?php

$Titulo = 'Comentario';

require '../utiles/PHPMailer/Exception.php';
require '../utiles/PHPMailer/PHPMailer.php';
require '../utiles/PHPMailer/SMTP.php';

include_once('./estructura/cabecera.php');

$datosIng = data_submitted();


if (EnviarMail($datosIng)) {
    echo "<script> window.location.href='./Consulta.php?accion=true'</script>";
} else {
    echo "<script> window.location.href='./Consulta.php?accion=false'</script>";;
}

include_once('./estructura/pie.php');
?>