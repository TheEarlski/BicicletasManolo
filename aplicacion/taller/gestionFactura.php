<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codFactura = $_GET["codFactura"];
$factura = $ACL->getFactura($codFactura);
$artsFactura = $ACL->dameArticulosDeFactura($codFactura);
$hojaTrabajo = $ACL->getHojaTrabajo($factura["codHojaTrabajo"]);
$bici = $ACL->getBicicleta($hojaTrabajo["codBici"]);
$cliente = $ACL->getCliente($bici["codCliente"]);
$tiposBicis = $ACL->dameTiposBicis();
$importeBaseTotal = $ACL->getImporteBaseTotal($codFactura);
$importeFinalTotal = $ACL->getImporteFinalTotal($codFactura);

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($bici, $artsFactura, $importeBaseTotal, $importeFinalTotal, $ACL, $codFactura, $cliente, $tiposBicis);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($bici, $artsFactura, $importeBaseTotal, $importeFinalTotal, $ACL, $codFactura, $cliente, $tiposBicis)
{
    ?>
    <main id="main">
        <section id="contact" class="section-bg wow fadeInUp">
            <div class="container">
        
                <div class="section-header">
                    <h2>Factura</h2>
                    <p>Gestiona la factura</p>
                </div>
                <div class="text-center">
    				<h4> Cliente: <strong><?php echo $cliente["nombre"].' '.$cliente["apellidos"];?></strong></h4>
    				<h4> 
    					Modelo de bicicleta: <strong><?php echo $bici["modelo"];?> </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    					Tipo de bicicleta: <strong> <?php echo $tiposBicis[$bici["codTipoBici"]];?></strong>
    				</h4>
        		</div>
                
            <div class="text-center">
            	<br>
            	<?php echo '<a target="_blank" href="/aplicacion/taller/pdfFactura.php?codFactura='.$codFactura.'">';?>
            	Descargar PDF <img src="/imagenes/pdfIcon.png">
            	</a>
            </div>
    		
    		<?php 
    		tablaFactura($artsFactura, $importeBaseTotal, $importeFinalTotal, $ACL);
    		?>
    		
    		</div>
    	</section>
	</main>
<?php
}

function tablaFactura($artsFactura, $importeBaseTotal, $importeFinalTotal, $ACL) {
    ?>
    <div class="text-center">
    <br>
        <table align="center">
        	<tr>
        		<th>Nombre artículo</th>
        		<th>Unidades</th>
        		<th>Importe Base</th>
        		<th>IVA</th>
        		<th>Descuento</th>
        		<th>Importe Final</th>
        	</tr>
        	
        <?php
        	$cont = 0;
        	foreach($artsFactura as $articuloFactura) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #fee7ea">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    echo '<td><a href="/aplicacion/taller/gestionArticuloFactura.php?codArtFactura='.$articuloFactura["codArticuloFactura"].'">'.$ACL->getArticulo($articuloFactura["codArticulo"])["nombre"].'</a></td>';
            	    echo '<td>'.$articuloFactura["unidades"].'</td>';
            	    echo '<td>'.sprintf('%0.2f', $articuloFactura["importeBase"])." €".'</td>';
            	    echo '<td>'.sprintf('%0.2f', $articuloFactura["iva"])." €".'</td>';
            	    echo '<td>'.$articuloFactura["descuento"]." %".'</td>';
            	    echo '<td>'.sprintf('%0.2f', $articuloFactura["importeFinal"])." €".'</td>';
            	    echo '</tr>';
        	}
        	?>
        	<tr class="total">
        		<th></th>
        		<th style="background-color: #d4dbf7">Importe base total:</th>
        		<th style="background-color: #d4dbf7"><?php echo sprintf('%0.2f', $importeBaseTotal)." €";?></th>
        		<th></th>
        		<th style="background-color: #d4dbf7">Importe final total:</th>
        		<th style="background-color: #d4dbf7"><?php echo sprintf('%0.2f', $importeFinalTotal)." €";?></th>
    		</tr>
        </table>
    </div>
    <?php
}