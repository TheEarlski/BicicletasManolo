<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codPresupuesto = $_GET["codPresupuesto"];
$presupuesto = $ACL->getPresupuesto($codPresupuesto);
$bici = $ACL->getBicicleta($presupuesto["codBici"]);
$cliente = $ACL->getCliente($bici["codCliente"]);
$tiposBicis = $ACL->dameTiposBicis();
$articulosPresupuesto = $ACL->getArticulosPresupuesto($codPresupuesto);
$importePresupuesto = $ACL->getImportePresupuesto($codPresupuesto);

$borrado = false;

if($_POST) {
    if(isset($_POST["borrar"])) {
        if($ACL->borrarPrespuesto($codPresupuesto))
            $borrado = true;
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($ACL, $bici, $cliente, $tiposBicis, $presupuesto, $importePresupuesto, $articulosPresupuesto, $borrado);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($ACL, $bici, $cliente, $tiposBicis, $presupuesto, $importePresupuesto, $articulosPresupuesto, $borrado)
{
    ?>
    <main id="main">
        <section id="contact" class="section-bg wow fadeInUp">
        <?php 
        if ($borrado) {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Borrado!</h2>
				<p>Se ha borrado el preupuesto<br> 
					<strong><a id="volverAlmacen" href="/aplicacion/taller/presupuestos.php">
						Volver a los presupuestos
					</a></strong>
				<p>
			</div>
			
		</div>
		<?php
        }           
        else {
        ?>
            <div class="container">
        
                <div class="section-header">
                    <h2>Presupuesto</h2>
                    <p>Gestiona el presupuesto</p>
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
    					Descripción: <strong><?php echo $presupuesto["descripcion"];?> </strong>
    				</h4><br>
        		</div>
        		
    		<?php
    		tablaPresupuesto($articulosPresupuesto, $importePresupuesto, $ACL);
    		?>
    		 	<hr>
    			<div class="form">
    				<form action="" method="post" role="form" class="contactForm">
    					<div class="text-center">
    						<h4>¿Deseas borrar este presupuesto?</h4>
    						<button name="borrar" type="submit">
    							<img src="/imagenes/taller/borrarBlanco.png" title="Borrar">
    						</button>
    					</div>
    				</form>
    			</div> 
			  		
    		</div>
    		<?php 
            }
            ?>
    	</section>
	</main>
<?php
}

function tablaPresupuesto($articulosPresupuesto, $importePresupuesto, $ACL) {
    ?>
    <div class="text-center">
    	
        <table align="center">
        	<tr>
        		<th>Nombre artículo</th>
        		<th>Unidades</th>
        		<th>Importe (€)</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($articulosPresupuesto as $articuloPresupuesto) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #fee7ea">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    echo '<td><a href="/aplicacion/taller/gestionArticuloPresupuesto.php?codArticuloPresupuesto='.$articuloPresupuesto["codArticuloPresupuesto"].'">'.$ACL->getArticulo($articuloPresupuesto["codArticulo"])["nombre"].'</a></td>'.
            	    '<td>'.$articuloPresupuesto["unidades"].'</td>'.
            	    '<td>'.sprintf('%0.2f', $articuloPresupuesto["importeFinal"]).'</td>'.
                    '</tr>';
        	}
        	?>
        	<tr style="border-top:solid 2px white;">
        		<th></th>
        		<th style="background-color: #d4dbf7">Precio calculado:</th>
        		<th style="background-color: #d4dbf7"><?php echo sprintf('%0.2f', $importePresupuesto["precioTotalCalculado"]); ?> €</th>
    		</tr>
    		<tr  style="border-top:solid 2px white;">
        		<th></th>
        		<th style="background-color: #d4dbf7">Precio final:</th>
        		<th style="background-color: #d4dbf7;"><?php echo sprintf('%0.2f', $importePresupuesto["precioPresupuesto"]); ?> €</th>
    		</tr>
        </table>
    </div><br>
    <?php
}