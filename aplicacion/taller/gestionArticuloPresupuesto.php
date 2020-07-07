<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codArtPresupuesto = $_GET["codArticuloPresupuesto"];
$artPresupuesto = $ACL->getArticuloPresupuesto($codArtPresupuesto);
$nombreArt = $ACL->getArticulo($artPresupuesto["codArticulo"])["nombre"];
$codPresupuesto = $artPresupuesto["codPresupuesto"];

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
        
        if($unidades != $artPresupuesto["unidades"]) {
            if($unidades <= 0) {
                $errores["Unidades"] = "Las unidades debe ser 1 o mas";
                $unidades = 1;
            }
            else {
                $datos["unidades"] = $unidades;
                $ACL->actualizarUnidadesArtPresupuesto($codArtPresupuesto, $unidades);
            }
        }
        
        //Importe
        $importeBase = 0;
        if(isset($_POST["importe"])) {
            $importeBase = $_POST["importe"];
        }
        
        if($importeBase != $artPresupuesto["importe"]) {
            if($importeBase < 0) {
                $errores["importe"] = "El importe no puede ser negativo";
                $importeBase = 0;
            }
            else {
                $datos["importe"] = $importeBase;
                $ACL->actualizarImporteArtPresupuesto($codArtPresupuesto, $importeBase);
            }
        }
        
        if(empty($errores)) {
            $actualizado = true;
        }
        
    }
    
    if(isset($_POST["borrar"])) {
//         if($ACL->borrarArtHojaTrabajo($codArtHoja));
        $borrado = true;
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $artPresupuesto, $nombreArt, $actualizado, $borrado, $codPresupuesto);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $artPresupuesto, $nombreArt, $modificado, $borrado, $codPresupuesto)
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
        			<p>Se ha borrado el artículo del prespuesto<br>
        				<strong>
        					<a id="volverAlmacen" href="/aplicacion/taller/gestionPresupuesto.php?codPresupuesto=<?php echo $codPresupuesto;?>">
        						Volver al prespuesto
        					</a>
        				</strong>
        			<p>
        		</div>
        	<?php
            } else if (!$modificado) {
            ?>
                <div class="section-header">
                    <h2>Artículo del presupuesto</h2>
                    <p>Gestiona el artículo de un prespuesto</p>
                </div>
    		
        		<?php 
        		formularioArticuloHoja($datos, $errores, $artPresupuesto, $nombreArt);
        		?>
        		<hr>
    			<div class="form">
    				<form action="" method="post" role="form" class="contactForm">
    					<div class="text-center">
    						<h4>¿Deseas borrar este artículo del presupuesto?</h4>
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
    					Se ha modificado el artículo del presupuesto con éxito<br>
    					<strong> 
    						<a id="volverAlmacen" href="/aplicacion/taller/gestionPresupuesto.php?codPrespuesto=<?php echo $codPresupuesto;?>">
    							Volver al presupuesto
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

function formularioArticuloHoja($datos, $errores, $artPresupuesto, $nombreArt) {
    
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
				value="<?php echo "{$artPresupuesto["unidades"]}"?>" required />
			<div class="validation"></div>
		</div>
		<!-- Importe -->
		<div class="form-group">
			<label for="importe">Importe final (€)</label>
			<input type="number" class="form-control" name="importe" id="importe" min="0" step=".01" 
				value="<?php echo "{$artPresupuesto["importeFinal"]}"?>" required />
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="modificar">Modificar</button>
		</div>

	</form>
</div>
<?php
}