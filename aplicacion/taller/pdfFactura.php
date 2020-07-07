<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");
require(dirname(__FILE__) . "/../../fpdf/fpdf.php");

$factura = [];
$codBici = 0;
$articulosFactura = [];

if(isset($_GET["codHojaTrabajo"])) {
    $codHojaTrabajo = $_GET["codHojaTrabajo"];
    $factura = $ACL->getFacturaConHoja($codHojaTrabajo);
}

if(isset($_GET["codFactura"])) {
    $codFactura = $_GET["codFactura"];
    $factura = $ACL->getFactura($codFactura);
}

$hojaTrabajo = $ACL->getHojaTrabajo($factura["codHojaTrabajo"]);
$codBici = $hojaTrabajo["codBici"];
$articulosFactura = $ACL->dameArticulosDeFactura($factura["codFactura"]);
$bici = $ACL->getBicicleta($codBici);
$cliente = $ACL->getCliente($bici["codCliente"]);

class pdfFactura extends FPDF {
    
    function Header() {
        $this->Image(dirname(__FILE__) . "/../../imagenes/logoBlack.png", 10, 10, -150);
        $this->SetFont('Arial','',12);
        $this->SetTextColor(14, 27, 77);
        define('GRADO',chr(176));
        $this->Text(165, 15,"Bicicletas Manolo");
        $this->Text(163, 20,"C/ Picadero, N".GRADO." 23");
        $this->Text(162, 25, "Antequera, Malaga");
        $this->Text(186, 30, "29200");
        
        $this->SetTextColor(248, 35, 74);
        $this->Text(147, 35, "Telefono: ");
        $this->SetTextColor(14, 27, 77);
        $this->Text(166, 35, "+34 952 534 398");
        
        $this->SetTextColor(248, 35, 74);
        $this->Text(118.5, 40, "Correo: ");
        $this->SetTextColor(14, 27, 77);
        $this->Text(133.5, 40, "bicicletasManolo2019@gmail.com");
        
        $this->Line(10, 45, $this->GetPageWidth()-10, 45);
    }
    
    function footer() {
        $this->SetFont('Arial','',12);
        $this->SetTextColor(14, 27, 77);
        $this->Text($this->GetPageWidth()/2-10, $this->GetPageHeight()-10, "N".GRADO." Pag. ".$this->PageNo());
    }
    
}

$pdf = new pdfFactura();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Factura_Bicicletas_Manolo_".Utilidades::fechaSqlANormalGuion($factura["fecha"]));

$pdf->SetY(45);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(14, 27, 77);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0, 10, "FACTURA", 0, 1, "C", true);

$pdf->SetTextColor(14, 27, 77);
$pdf->SetFont('Arial','',14);
$pdf->Text(12, 65, "Fecha: ".Utilidades::fechaSqlANormal($factura["fecha"]));
$pdf->Text(83, 65, "N".GRADO." de factura: ".sprintf("%04d", $factura["codFactura"]));

$pdf->SetFont('Arial','B',18);
$pdf->SetY(71);
$pdf->SetFillColor(248, 35, 74);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(0, 10, "FACTURAR A", 0, 1, "C", true);

$pdf->SetTextColor(14, 27, 77);
$pdf->SetFont('Arial','',14);
$pdf->Text(12, 90, "Nombre y apellidos: ".$cliente["nombre"]." ".$cliente["apellidos"]);
$pdf->Text(12, 100, "Direccion: ".str_replace("º", GRADO, $cliente["direccion"]));
$pdf->Text(12, 110, "Telefono: ".$cliente["telefono"]);
$pdf->Text(12, 120, "Correo: ".$cliente["correo"]);

$pdf->SetY(130);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(14, 27, 77);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFont('Arial','B',16);
$pdf->SetLineWidth(0.4);

define('EURO',chr(128));

$pdf->Cell(70, 10, "DESCRIPCION", 1, 0, "C", true);
$pdf->Cell(20, 10, "UDS", 1, 0, "C", true);
$pdf->Cell(27.5, 10, "COSTE", 1, 0, "C", true);
$pdf->Cell(22.5, 10, "IVA", 1, 0, "C", true);
$pdf->Cell(20, 10, "DTO.", 1, 0, "C", true);
$pdf->Cell(30, 10, "FINAL", 1, 1, "C", true);

$pdf->SetTextColor(14, 27, 77);
$pdf->SetFont('Arial','',14);
$pdf->SetDrawColor(14, 27, 77);

define('PERCENT',chr(37));

$importeManoObra = $ACL->getImporteTotalObraMano($hojaTrabajo["codHojaTrabajo"]);
$pdf->Cell(70, 8, "Mano de obra", 1, 0, "C");
$pdf->Cell(20, 8, "-", 1, 0, "C");
$pdf->Cell(27.5, 8, sprintf('%0.2f', $importeManoObra)." ".EURO, 1, 0, "C");
$pdf->Cell(22.5, 8, "-", 1, 0, "C");
$pdf->Cell(20, 8, "-", 1, 0, "C");
$pdf->Cell(30, 8, sprintf('%0.2f', $importeManoObra)." ".EURO, 1, 1, "C");

for($i=0; $i<15; $i++) {
    if($i < count($articulosFactura)) {
        if($ACL->getArticulo($articulosFactura[$i]["codArticulo"])["codTipoArticulo"] == 3) {
            continue;
        }
        $descripcion = $ACL->getArticulo($articulosFactura[$i]["codArticulo"])["nombre"];
        $pdf->Cell(70, 8, $descripcion, 1, 0, "C");
        $pdf->Cell(20, 8, $articulosFactura[$i]["unidades"], 1, 0, "C");
        $pdf->Cell(27.5, 8, sprintf('%0.2f', $articulosFactura[$i]["importeBase"])." ".EURO, 1, 0, "C");
        $pdf->Cell(22.5, 8, sprintf('%0.2f', $articulosFactura[$i]["iva"])." ".EURO, 1, 0, "C");
        $pdf->Cell(20, 8, $articulosFactura[$i]["descuento"]." ".PERCENT, 1, 0, "C");
        $pdf->Cell(30, 8, sprintf('%0.2f', $articulosFactura[$i]["importeFinal"])." ".EURO, 1, 1, "C");
    }
    else {
        $pdf->Cell(70, 8, " ", 1, 0, "C");
        $pdf->Cell(20, 8, " ", 1, 0, "C");
        $pdf->Cell(27.5, 8, " ", 1, 0, "C");
        $pdf->Cell(22.5, 8, " ", 1, 0, "C");
        $pdf->Cell(20, 8, " ", 1, 0, "C");
        $pdf->Cell(30, 8, " ", 1, 1, "C");
    }
}

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(14, 27, 77);
$pdf->SetDrawColor(14, 27, 77);
$pdf->SetFont('Arial','B',16);

$pdf->Cell(160, 10, "TOTAL: ", 1, 0, "R", true);
$pdf->SetFillColor(248, 35, 74);
$pdf->SetDrawColor(248, 35, 74);
$pdf->Cell(30, 10, sprintf('%0.2f', $ACL->getImporteFinalTotal($factura["codFactura"]))." ".EURO, 1, 0, "C", true);

$pdf->Output("", "Factura_Bicicletas_Manolo_".Utilidades::fechaSqlANormalGuion($factura["fecha"]).".pdf");
?>