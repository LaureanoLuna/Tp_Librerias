<?php
require __DIR__.'/../utiles\PHPAuth\vendor\autoload.php';
require_once('funciones.php');

include_once('..\utiles\PHPAuth\vendor\autoload.php');
include_once('..\modelo\conector\BaseDatos.php');
$BASEDATOS = new BaseDatos();
// CREAMOS UNA INSTANCIA DE LA CLASE PARA UTILIZAR SUS MÉTODOS EN EL ABM POSTERIORMENTE
$AUTH = new \Delight\Auth\Auth($BASEDATOS);

