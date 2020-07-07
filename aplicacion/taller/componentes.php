<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$componentes = $ACL->dameComponentes();

echo json_encode($componentes);






