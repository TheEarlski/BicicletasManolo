<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$bicis = [];
$tiposBicis = [];
$clientes = [];
$codCliente = 0;

$Acceso = new Acceso();
if ($Acceso->hayUsuario()) {
    $codCliente = $ACL->getCodigoCliente($Acceso->getCorreo());
    $bicis = $ACL->getBicisCliente($codCliente);
    $tiposBicis = $ACL->dameTiposBicis();
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($bicis, $tiposBicis, $clientes, $Acceso, $ACL);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
	<?php
}

function cuerpo($bicis, $tiposBicis, $clientes, $acceso, $ACL)
{
    ?>
<section id="contact" class="section-bg wow fadeInUp">

	<div class="container">

		<div class="section-header">
			<h2>Mis bicicletas</h2>
			<p>Mira el estado de tus bicicletas</p>
		</div>
    <?php
    if (! $acceso->hayUsuario()) {
        ?>
        	<div class="form">
			<div class="text-center">
				<h4>Es necesario iniciar sesión</h4>
				<button type="submit" class="button"
					onclick="window.location.href='/aplicacion/inicio/login.php';">Inicia
					sesión</button>
			</div>
		</div>
        <?php
    } else {
        ?>
        	<div class="text-center">
			<h4><strong>Mis bicicletas entregadas y su estado</strong></h4>
        <?php
        if (! empty($bicis)) {
            tablaBicisEnRep($bicis, $tiposBicis, $clientes, $ACL);
        } else {
            ?>
           		<h3>No tiene ninguna bicicleta entregada en nuestra tienda</h3>
           	<?php
        }
    }
    ?>	</div>
	</div>
</section>
<?php
}

function tablaBicisEnRep($bicis, $tiposBicis, $clientes, $ACL)
{
    ?>
	<table>
		<tr>
			<th></th>
			<th>Modelo</th>
			<th>Tipo de bicicleta</th>
			<th>Descripción</th>
			<th>Marchas</th>
			<th>Ruedas (pulgadas)</th>
			<th>Peso (kg)</th>
			<th>Material</th>
		</tr>
        	
    	<?php
    foreach ($bicis as $bici) {
        echo '<tr style="background-color: #b7c2f2; border-top: solid 25px #FFFFFF;">';
        echo '<th style="margin-top: 10px;">Bicicleta:</th>' .
            '<td>' . $bici["modelo"] . '</td>' . 
            '<td>' . $tiposBicis[$bici["codTipoBici"]] . '</td>' . 
            '<td>' . $bici["descripcion"] . '</td>' .
            '<td>' . $bici["marchas"] . '</td>' .
            '<td>' . $bici["tamanioRuedas"] . '</td>' .
            '<td>' . $bici["peso"] . '</td>' .
            '<td>' . $bici["material"] . '</td>' .
            '</tr>';
        
        $hojas = $ACL->getHojasTrabajoFiltrados("codBici", $bici["codBici"]);
        foreach($hojas as $hoja) {
            echo '<tr style="background-color: #fee7ea; border-top: solid 3px #FFFFFF">'.
                '<th rowspan="2">Entrega:</th>' .
                '<th colspan="2">Problema</th>'.
                '<th>Fecha</th>';
            if($hoja["reparada"] != 0) {
                echo '<th colspan="2">Factura</th>';
            }
            echo '<th colspan="4">'.
                '</tr>';
            echo '<tr  style="background-color: #fee7ea;">' .
                '<td colspan="2">'.$hoja["problema"].'</td>' .
                '<td>'.Utilidades::fechaSqlANormalGuion($hoja["fechaApertura"]);
            echo $hoja["fechaCierre"]!=""?' a '.Utilidades::fechaSqlANormalGuion($hoja["fechaCierre"]):"";
            echo '</td>';
            if($hoja["reparada"] != 0) {
                echo '<td colspan="2"><a target="_blank" href="/aplicacion/taller/pdfFactura.php?codHojaTrabajo='.$hoja["codHojaTrabajo"].'"><img src="/imagenes/pdfIcon.png" title="Factura pdf" alt="Icono pdf"></a></td>';;
            }
                
            echo '<th colspan="4">'.
                '</tr>';
            
            $estados = $ACL->getEstados($hoja["codHojaTrabajo"]);
            foreach ($estados as $estado) {
                echo '<tr style="background-color: #d4dbf7; border-top: solid 3px #FFFFFF;">' . 
                    '<td><strong>Estado:</strong></td>' .
                    '<td colspan="2"><strong>' . Utilidades::fechaSqlANormalGuion($estado["fecha"]) . '</strong></td>' .
                    '<td><strong>' . $estado["estado"] . '</strong></td>' .
                    '<td colspan="4"><strong>'.
                    '</tr>';
            }
        }
    }
    ?>	
    </table>
<?php
}