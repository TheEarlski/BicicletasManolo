<?php

include_once(dirname(__FILE__)."/../../cabecera.php");

$Acceso->quitarRegistroUsuario();

header("location: /index.php");