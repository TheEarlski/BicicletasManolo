<?php
define("RUTABASE", dirname(__FILE__));

// define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO", "desarrollo"); // en "produccion o en desarrollo

if (MODO_TRABAJO == "produccion")
    error_reporting(0);
else
    error_reporting(E_ALL);

spl_autoload_register(function ($clase) {
    $ruta = RUTABASE . "/scripts/clases/";
    $fichero = $ruta . "$clase.php";
    if (file_exists($fichero)) {
        require_once ($fichero);
    } else {
        throw new Exception("La clase $clase no se ha encontrado.");
    }
});

session_start();

require_once (RUTABASE . "/aplicacion/config/parametrosBD.php");

$ACL = new ACLBD($BDhost, $BDusuario, $BDcontra, $BDbd);
$Acceso = new Acceso();

include (RUTABASE . "/aplicacion/plantilla/plantilla.php");
//include (RUTABASE . "/aplicacion/config/acceso_bd.php");
