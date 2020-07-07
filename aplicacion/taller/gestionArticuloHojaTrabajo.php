<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codArtHoja = $_GET["codArtHoja"];
$artHoja = $ACL->getArticuloHojaTrabajo($codArtHoja);
$nombreArt = $ACL->getArticulo($artHoja["codArticulo"])["nombre"];
$codHojaTrabajo = $artHoja["codHojaTrabajo"];

$datos = [
    "unidades"=>1,
    "importe"=>0
];
$errores = [];

$actualizado = false;
$borrado = false;

if(isset($_POST)) {
    if(isset($_POST["modificar"])) {
        
        //Unidades
        $unidades = 0;
        if(isset($_POST["unidades"])) {
            $unidades = intval($_POST["unidades"]);
        }
        
        if($unidades != $artHoja["unidades"]) {
            if($unidades <= 0) {
                $errores["Unidades"] = "Las unidades debe ser 1 o mas";
                $unidades = 1;
            }
            else {
                $datos["unidades"] = $unidades;
                $ACL->actualizarUnidadesArtHoja($codArtHoja, $unidades);
                $ACL->actualizarArticuloFacturaDesdeHoja('unidades', $unidades, $codArtHoja);
            }
        }
        
        //Importe
        $importe = 0;
        if(isset($_POST["importe"])) {
            $importe = intval($_POST["importe"]);
        }
        
        if($importe < 0) {
            $errores["importe"] = "El importe no puede ser negativo";
            $importe = 0;
        }
        
        if($importe != intval($artHoja["importe"])) {
            $ACL->actualizarImporteArtHoja($codArtHoja, $importe);
            $ACL->actualizarArticuloFacturaDesdeHoja('importeBase', $importe, $codArtHoja);
            
            $articulo = $ACL->getArticulo($artHoja["codArticulo"]);
            
            if(intval($articulo["codTipoArticulo"]) == 3) {
                $ACL->actualizarArticuloFacturaDesdeHoja('importeFinal', $importe*$unidades, $codArtHoja);
            }
            else {
                $ACL->actualizarArticuloFacturaDesdeHoja('iva', $importe*0.21, $codArtHoja);
                $ACL->actualizarArticuloFacturaDesdeHoja('importeFinal', ($importe*1.21)*$unidades, $codArtHoja);
            }
        }
        
        if(empty($errores)) {
            $actualizado = true;
        }
        
    }
    
    if(isset($_POST["borrar"])) {
        if($ACL->borrarArtHojaTrabajo($codArtHoja)) {
            $ACL->borrarArtFacturaConArtHoja($codArtHoja);
            $borrado = true;
        }
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $artHoja, $nombreArt, $actualizado, $borrado, $codHojaTrabajo, $ACL);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $artHoja, $nombreArt, $modificado, $borrado, $codHojaTrabajo, $ACL)
{
    ?>
    <main id="main">
        <section id="contact" class="section-bg wow fadeInUp">
            <div class="container">
            <?php
            if ($borrado) {
            ?>
        		<div class="section-header">
        			<h2>¡Borrado!</h2>
        			<p>Se ha borrado el artículo de la hoja de trabajo<br>
        				<strong>
        					<a id="volverAlmacen" href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo=<?php echo $codHojaTrabajo;?>">
        						Volver a la hoja de trabajo
        					</a>
        				</strong>
        			<p>
        		</div>
        	<?php
            } else if (!$modificado) {
            ?>
                <div class="section-header">
                    <h2>Artículo de la hoja de trabajo</h2>
                    <p>Gestiona el artículo de una hoja de trabajo</p>
                </div>
    		
        		<?php 
        		formularioArticuloHoja($datos, $errores, $artHoja, $nombreArt, $ACL);
        		?>
        		<hr>
    			<div class="form">
    				<form action="" method="post" role="form" class="contactForm">
    					<div class="text-center">
    						<h4>¿Deseas borrar este artículo de la hoja de trabajo?</h4>
    						<button name="borrar" type="submit">
    							<img src="/imagenes/taller/borrarBlanco.png" title="Borrar">
    						</button>
    					</div>
    				</form>
    			</div>
			<?php 
            } else {
			?>
    			<div class="section-header">
    				<h2>¡Modificado!</h2>
    				<p>
    					Se ha modificado el artículo de la hoja de trabajo con éxito<br>
    					<strong> 
    						<a id="volverAlmacen" href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo=<?php echo $codHojaTrabajo;?>">
    							Volver a la hoja de trabajo
    						</a>
    					</strong>
    				<p>
				</div>
			<?php 
            }
			?>
			</div>
    	</section>
	</main>
<?php
}

function formularioArticuloHoja($datos, $errores, $artHoja, $nombreArt, $ACL) {
    
    if ($errores) { // mostrar los errores
        echo "<div class='error' style='color: red'>Errores<br>";
        foreach ($errores as $clave => $error) {
            echo "$clave: $error<br>" . PHP_EOL;
        }
        echo "</div><br>" . PHP_EOL;
    }
    ?>
<div class="form">
	<form action="" method="post" role="form" class="contactForm">
		<!-- Nombre artículo -->
		<div class="form-group">
			<label for="nombreArt">Nombre del artículo</label>
			<input type="text" class="form-control" name="nombreArt" id="nombreArt" value="<?php echo $nombreArt;?>" disabled/>
			<div class="validation"></div>
		</div>
		<!-- Unidades -->
		<div class="form-group">
			<label for="unidades">Unidades</label>
			<input type="number" class="form-control" name="unidades" id="unidades" min="1"  
				value="<?php echo "{$artHoja["unidades"]}"?>" required
				<?php if(intval($ACL->getArticulo($artHoja["codArticulo"])["codTipoArticulo"]) == 3) echo " readonly";?> />
			<div class="validation"></div>
		</div>
		<!-- Importe -->
		<div class="form-group">
			<label for="importe"></label>
			<input type="number" class="form-control" name="importe" id="importe" min="0" step=".01" 
				value="<?php echo "{$artHoja["importe"]}"?>" required />
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="modificar">Modificar</button>
		</div>

	</form>
</div>
<?php
}