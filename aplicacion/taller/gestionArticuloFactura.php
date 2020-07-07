<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codArtFactura = $_GET["codArtFactura"];
$artFactura = $ACL->getArticuloFactura($codArtFactura);
$nombreArt = $ACL->getArticulo($artFactura["codArticulo"])["nombre"];

$datos = [
    "unidades"=>1,
    "importeBase"=>0.0,
    "iva"=>0.0,
    "descuento"=>0.0,
    "importeFinal"=>0.0
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
        
        if($unidades != $artFactura["unidades"]) {
            if($unidades <= 0) {
                $errores["Unidades"] = "Las unidades debe ser 1 o mas";
                $unidades = 1;
            }
            else {
                $datos["unidades"] = $unidades;
                $ACL->actualizarArticuloFactura('unidades',$unidades,$codArtFactura);
            }
        }
        
        //Importe
        $importeBase = 0.0;
        if(isset($_POST["importeBase"])) {
            $importeBase = floatval($_POST["importeBase"]);
        }
        
        if($importeBase != $artFactura["importeBase"]) {
            if($importeBase < 0) {
                $errores["importeBase"] = "El importe no puede ser negativo";
                $importeBase = 0;
            }
            else {
                $datos["importeBase"] = $importeBase;
                $ACL->actualizarArticuloFactura('importeBase', $importeBase, $codArtFactura);
            }
        }
        
        //IVA
        $iva = 0.0;
        if(isset($_POST["iva"])) {
            $iva = floatval($_POST["iva"]);
        }
        
        if($iva != $artFactura["iva"]) {
            if($iva < 0) {
                $errores["iva"] = "El iva no puede ser negativo";
                $iva = 0;
            }
            else {
                $datos["iva"] = $iva;
                $ACL->actualizarArticuloFactura('iva', $iva, $codArtFactura);
            }
        }
        
        //Descuento
        $descuento = 0.0;
        if(isset($_POST["descuento"])) {
            $descuento = floatval($_POST["descuento"]);
        }
        
        if($descuento != $artFactura["descuento"]) {
            if($descuento < 0) {
                $errores["descuento"] = "El descuento no puede ser negativo";
                $descuento = 0;
            }
            else if($descuento > 100) {
                $errores["descuento"] = "El descuento no puede ser superior a 100";
                $descuento = 0;
            }
            else {
                $datos["descuento"] = $descuento;
                $ACL->actualizarArticuloFactura('descuento', $descuento, $codArtFactura);
            }
        }
        
        //ImporteFinal
        $importeFinal = 0.0;
        if(isset($_POST["importeFinal"])) {
            $importeFinal = floatval($_POST["importeFinal"]);
        }
        
        if($importeFinal != $artFactura["importeFinal"]) {
            if($importeFinal < 0) {
                $errores["importeFinal"] = "El importe no puede ser negativo";
                $importeFinal = 0;
            }
            else {
                $datos["importeFinal"] = $importeFinal;
                $ACL->actualizarArticuloFactura('importeFinal', $importeFinal, $codArtFactura);
            }
        }
        
        if(empty($errores)) {
            $actualizado = true;
        }
        
    }
    
    if(isset($_POST["borrar"])) {
        if($ACL->borrarArtFactura($codArtFactura))
            $borrado = true;
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $artFactura, $nombreArt, $actualizado, $borrado, $ACL);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
	<script src="/javascript/taller/gestionArticuloFactura.js"></script>
    <?php
}

function cuerpo($datos, $errores, $artFactura, $nombreArt, $modificado, $borrado, $ACL)
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
        			<p>Se ha borrado el artículo de la factura<br>
        				<strong>
        					<a id="volverAlmacen" href="/aplicacion/taller/gestionFactura.php?codFactura=<?php echo $artFactura["codFactura"];?>">
        						Volver a la factura
        					</a>
        				</strong>
        			<p>
        		</div>
        	<?php
            } else if (!$modificado) {
            ?>
                <div class="section-header">
                    <h2>Artículo de la factura</h2>
                    <p>Gestiona el artículo de una factura</p>
                </div>
    		
        		<?php 
        		formularioArticuloFactura($datos, $errores, $artFactura, $nombreArt, $ACL);
        		?>
        		<hr>
    			<div class="form">
    				<form action="" method="post" role="form" class="contactForm">
    					<div class="text-center">
    						<h4>¿Deseas borrar este artículo de la factura?</h4>
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
    					Se ha modificado el artículo de la factura con éxito<br>
    					<strong> 
    						<a id="volverAlmacen" href="/aplicacion/taller/gestionFactura.php?codFactura=<?php echo $artFactura["codFactura"];?>">
    							Volver a la factura
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

function formularioArticuloFactura($datos, $errores, $artFactura, $nombreArt, $ACL) {
    
    if ($errores) { // mostrar los errores
        echo "<div class='error' style='color: red'>Errores<br>";
        foreach ($errores as $clave => $error) {
            echo "$error<br>" . PHP_EOL;
        }
        echo "</div><br>" . PHP_EOL;
    }
    ?>
<div class="form">
	<form action="" method="post" role="form" class="contactForm">
		<input type="hidden" id="tipoArt" value="<?php echo $ACL->getArticulo($artFactura["codArticulo"])["codTipoArticulo"];?>">
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
				value="<?php echo "{$artFactura["unidades"]}"?>" required 
				<?php if(intval($ACL->getArticulo($artFactura["codArticulo"])["codTipoArticulo"])==3) echo " readonly";?> />
			<div class="validation"></div>
		</div>
		<!-- Importe Base -->
		<div class="form-group">
			<label for="importeBase">Importe Base (€)</label>
			<input type="number" class="form-control" name="importeBase" id="importeBase" min="0" step=".01" 
				value="<?php echo "{$artFactura["importeBase"]}"?>" required />
			<div class="validation"></div>
		</div>
		<!-- Iva -->
		<div class="form-group">
			<label for="iva">IVA (€)</label>
			<input type="number" class="form-control" name="iva" id="iva" min="0" step=".01" 
				value="<?php echo "{$artFactura["iva"]}"?>" required readonly />
			<div class="validation"></div>
		</div>
		<!-- Descuento -->
		<div class="form-group">
			<label for="descuento">Descuento (%)</label>
			<input type="number" class="form-control" name="descuento" id="descuento" min="0" 
				value="<?php echo floatval($artFactura["descuento"]);?>" required />
			<div class="validation"></div>
		</div>
		<!-- Importe Final -->
		<div class="form-group">
			<label for="importeFinal">Importe Final (€). Se calculará según los otros campos</label>
			<input type="number" class="form-control" name="importeFinal" id="importeFinal" min="0" step=".01" 
				value="<?php echo "{$artFactura["importeFinal"]}"?>" required readonly />
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="modificar">Modificar</button>
		</div>

	</form>
</div>
<?php
}