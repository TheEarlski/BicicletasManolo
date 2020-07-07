<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codHoja = $_GET["codHojaTrabajo"];
$hojaTrabajo = $ACL->getHojaTrabajo($codHoja);
$bici = $ACL->getBicicleta($hojaTrabajo["codBici"]);
$cliente = $ACL->getCliente($bici["codCliente"]);
$tiposBicis = $ACL->dameTiposBicis();
$articulosHoja = $ACL->dameArticulosDeHojaTrabajo($codHoja);
$importeTotal = $ACL->getImporteTotal($codHoja);
$estados = $ACL->getEstados($codHoja);

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($articulosHoja, $ACL, $importeTotal, $bici, $cliente, $tiposBicis, $hojaTrabajo, $codHoja, $estados);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($articulosHoja, $ACL, $importeTotal, $bici, $cliente, $tiposBicis, $hojaTrabajo, $codHoja, $estados)
{
    ?>
    <main id="main">
        <section id="contact" class="section-bg wow fadeInUp">
            <div class="container">
        
                <div class="section-header">
                    <h2>Hoja de trabajo</h2>
                    <p>Gestiona la hoja de trabajo</p>
                </div>
                <div class="text-center">
    				<h4>
    					Cliente: <strong><?php echo $cliente["nombre"].' '.$cliente["apellidos"];?></strong>
					</h4>
    				<h4> 
    					Modelo de bicicleta: <strong><?php echo $bici["modelo"];?> </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    					Tipo de bicicleta: <strong> <?php echo $tiposBicis[$bici["codTipoBici"]];?></strong>
    				</h4>
    				<h4>
    					Fecha apertura: <strong><?php echo Utilidades::fechaSqlANormal($hojaTrabajo["fechaApertura"])?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Fecha clausura: <strong><?php echo $hojaTrabajo["fechaCierre"]==""?"-":Utilidades::fechaSqlANormal($hojaTrabajo["fechaCierre"])?></strong>
					</h4>
					<h4>Problema: <strong><?php echo $hojaTrabajo["problema"];?></strong> </h4>
        		</div>
                
            <hr>
            
            <div class="text-center">
            	<h4><strong>Estados</strong></h4>
            	
            	<a id="añadir" href="./nuevoEstado.php?codHojaTrabajo=<?php echo $codHoja;?>">Actualizar o añadir un nuevo estado
    				<img id="plus" src="/imagenes/taller/plus.png">
    			</a>
            	
            	<p><img src="/imagenes/taller/infoBlue.png" height="16px"> Haz click en el estado para modificar o borrarlo</p>
            	<?php 
            	tablaEstados($estados);
            	?>
			</div> 
			          
            <hr>
            
    		<div class="text-center">
    			<h4><strong>Articulos de la hoja de trabajo</strong></h4>
            	<?php echo '<a target="_blank" href="/aplicacion/taller/pdfHojaTrabajo.php?codHojaTrabajo='.$codHoja.'">';?>
            	Descargar PDF <img src="/imagenes/pdfIcon.png">
            	</a><br>
            </div>
    		<?php 
    		tablaHoja($articulosHoja, $ACL, $importeTotal);
    		
    		if($hojaTrabajo["reparada"] != 0) {
    		?>
            <div class="text-center">
    			<a id="añadir" href="/aplicacion/taller/nuevoArticuloHojaTrabajo.php?codHojaTrabajo=<?php echo $hojaTrabajo["codHojaTrabajo"];?>">Añadir un artículo
    				<img id="plus" src="/imagenes/taller/plus.png">
    			</a>
    		</div>
    		<?php 
    		}
    		?>
    		
    		</div>
    	</section>
	</main>
<?php
}

function tablaEstados($estados) {
    ?>
    <div class="text-center">
        <table align="center">
        	<tr>
        		<th>Estado</th>
        		<th>Fecha</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($estados as $estado) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #d4dbf7">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    echo '<td><a href="/aplicacion/taller/gestionEstado.php?codEstado='.$estado["codEstado"].'">'.$estado["estado"].'</a></td>'.
            	    '<td><a href="/aplicacion/taller/gestionEstado.php?codEstado='.$estado["codEstado"].'">'.Utilidades::fechaSqlANormalGuion($estado["fecha"]).'</a></td>'.
                    '</tr>';
        	}
        	?>
        </table>
    </div><br>
    <?php
}

function tablaHoja($articulosHoja, $ACL, $importeTotal) {
    ?>
    <div class="text-center">
    	
        <table align="center">
        	<tr>
        		<th>Nombre artículo</th>
        		<th>Fecha</th>
        		<th>Unidades</th>
        		<th>Importe (€)</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($articulosHoja as $articuloHoja) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #fee7ea">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    echo '<td><a href="/aplicacion/taller/gestionArticuloHojaTrabajo.php?codArtHoja='.$articuloHoja["codArtHoja"].'">'.$ACL->getArticulo($articuloHoja["codArticulo"])["nombre"].'</a></td>'.
            	    '<td><a href="/aplicacion/taller/gestionArticuloHojaTrabajo.php?codArtHoja='.$articuloHoja["codArtHoja"].'">'.Utilidades::fechaSqlANormalGuion($articuloHoja["fecha"]).'</a></td>'.
            	    '<td>'.$articuloHoja["unidades"].'</td>'.
            	    '<td>'.$articuloHoja["importe"].'</td>'.
                    '</tr>';
        	}
        	?>
        	<tr>
        		<th colspan="2"></th>
        		<th style="background-color: #d4dbf7">Importe total:</th>
        		<th style="background-color: #d4dbf7"><?php echo $importeTotal ?></th>
    		</tr>
        </table>
        <br>
    </div><br>
    <?php
}