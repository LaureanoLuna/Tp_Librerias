<?php

$GLOBALS['ROOT'] =$_SERVER['DOCUMENT_ROOT']."/TP_Librerias/";

// FUNCIONES data_submitted Y spl_autoload_register
// TAMBIÉN FUNCIONES QUE USAN LA LIBRERIA PHPMailer
// CREA INSTANCIA DE LA LIBRERIA PHPAuth Y LA CONECTA CON LA BASE DE DATOS
include_once("utiles/funciones.php");

// ENCONTRARÁN LAS CONSULTAS SQL PARA GENERAR LAS TABLAS EN LA CARPETA "database" (ESTO VIENE CON LA LIBRERIA)

//MODIFICAR SEGÚN TENGAS EL PROYECTO

$PROYECTO ='TP_Librerias';

//variable que almacena el directorio del proyecto
$ROOT =$_SERVER['DOCUMENT_ROOT']."/$PROYECTO/";

?>