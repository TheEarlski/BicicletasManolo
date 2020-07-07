<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");
require(dirname(__FILE__) . "/../../fpdf/fpdf.php");

$codHojaTrabajo = $_GET["codHojaTrabajo"];
$hojaTrabajo = $ACL->getHojaTrabajo($codHojaTrabajo);
$codBici = $hojaTrabajo["codBici"];
$bici = $ACL->getBicicleta($codBici);
$cliente = $ACL->getCliente($ACL->getBicicleta($codBici)["codCliente"]);
$artsHoja = $ACL->dameArticulosDeHojaTrabajo($hojaTrabajo["codHojaTrabajo"]);
$importeTotal = $ACL->getImporteTotal($hojaTrabajo["codHojaTrabajo"]);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',16);

$pdf->SetTitle("Hoja_de_trabajo_Bicicletas_Manolo_".$cliente["apellidos"]);
$pdf->Text(40, 15, "HOJA DE TRABAJO DE BICICLETAS MANOLO");
$pdf->SetLineWidth(1);
$pdf->Line(10, 18, 200, 18);
$pdf->SetLineWidth(0.5);
$pdf->Text(63, 30, "CLIENTE: ".mb_strtoupper($cliente["nombre"]). " ".mb_strtoupper($cliente["apellidos"]));
$pdf->Line(50, 33, 150, 33);
$pdf->Text(47, 43, "MODELO DE BICICLETA: ". mb_strtoupper($bici["modelo"]));
$pdf->Line(40, 46, 160, 46);    
$pdf->Text(55, 56, "FECHA DE APERTURA: ".Utilidades::fechaSqlANormalGuion($hojaTrabajo["fechaApertura"]));
$pdf->Line(45, 59,155, 59);
if($hojaTrabajo["fechaCierre"] != "") {
    $fechaCierre = Utilidades::fechaSqlANormalGuion($hojaTrabajo["fechaCierre"]);
}
$pdf->Text(55, 69, $hojaTrabajo["fechaCierre"]!=""?"FECHA DE CLAUSURA: ".$fechaCierre:"FECHA DE CLAUSURA: -");
$pdf->Line(45, 72, 155, 72);

//Tabla
$pdf->Ln();
$pdf->SetY(80);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50, 10, "FECHA", 1, 0, "C");
$pdf->Cell(70, 10, "ARTICULO", 1, 0, "C");
$pdf->Cell(30, 10, "UNIDADES", 1, 0, "C");
define('EURO',chr(128));
$pdf->Cell(40, 10, "IMPORTE (".EURO.")", 1, 0, "C");
$pdf->Ln();


foreach($artsHoja as $artHoja) {
    $articulo = $ACL->getArticulo($artHoja["codArticulo"]);
    $pdf->Cell(50, 10, Utilidades::fechaSqlANormalGuion($artHoja["fecha"]), 1, 0, "C");
    $pdf->Cell(70, 10, mb_strtoupper($articulo["nombre"]), 1, 0, "C");
    $pdf->Cell(30, 10, $artHoja["unidades"], 1, 0, "C");
    $pdf->Cell(40, 10, $artHoja["importe"], 1, 0, "C");
    $pdf->Ln();
}

$pdf->Cell(150, 10, "IMPORTE TOTAL: ", 1, 0, "R");
$pdf->Cell(40, 10, $importeTotal, 1, 0, "C");

$pdf->Output();
?>