<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codHojaTrabajo = $_GET["codHojaTrabajo"];
$articulos = $ACL->dameArticulos();

$datos = [
    "codArticulo"=>0,
    "unidades"=>1
];
$errores = [];

$añadido = false;

if ($_POST) {
    if(isset($_POST["anadir"])) {
        //Articulo
        $codArticulo = 0;
        if(isset($_POST["articulo"])) {
            $codArticulo = intval($_POST["articulo"]);
        }
        
        if($codArticulo == 0) {
            $errores["articulo"] = "Debe elegir un articulo";
        }
        
        $datos["codArticulo"] = $codArticulo;
        
        //Unidades
        $unidades = 1;
        if(isset($_POST["unidades"])) {
            $unidades = intval($_POST["unidades"]);
        }
        
        if($unidades <= 0) {
            $errores["unidades"] = "Las unidades no pueden ser inferior a 1";
            $unidades = 1;
        }
        
        $datos["unidades"] = $unidades;
        
        if(empty($errores)) {
            $articulo = $ACL->getArticulo($datos["codArticulo"]);
            
            $codArtHojaTrabajo = $ACL->anadirArticuloHojaTrabajo($datos["codArticulo"], $codHojaTrabajo, $datos["unidades"], $articulo["precioVenta"]*$datos["unidades"]);
            $articuloHoja = $ACL->getArticuloHojaTrabajo($codArtHojaTrabajo);
            $factura = $ACL->getFacturaConHoja($articuloHoja["codHojaTrabajo"]);
            
            if(intval($articulo["codTipoArticulo"]) != 3) {
                $ACL->anadirArticuloFactura($articuloHoja["unidades"], $articuloHoja["importe"]/1.21, $articuloHoja["importe"]-($articuloHoja["importe"]/1.21), 0, $articuloHoja["importe"]*$articuloHoja["unidades"],
                    $articuloHoja["codArticulo"], $factura["codFactura"], $articuloHoja["codArtHoja"]);
            }
            else {
                $ACL->anadirArticuloFactura($articuloHoja["unidades"], $articuloHoja["importe"], 0, 0, $articuloHoja["importe"],
                    $articuloHoja["codArticulo"], $factura["codFactura"], $articuloHoja["codArtHoja"]);
            }
            
            $añadido = true;
        }
        
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $articulos, $añadido, $codHojaTrabajo);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $articulos, $añadido, $codHojaTrabajo)
{
    ?>
<main id="main">

	<!--==========================
      Register Section
    ============================-->
	<section id="contact" class="section-bg wow fadeInUp">

			<?php
    if (! empty($errores) || ! $añadido) {
        ?>
    	    <div class="container">

			<div class="section-header">
				<h2>Añadir artículo</h2>
				<p>Añadir un artículo nuevo</p>
			</div>
    			    <?php
        formulario($datos, $errores, $articulos);
        ?>
			</div>
        <?php
    } else {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Añadido!</h2>
				<p>Se ha añadido el artículo con éxito</p>
				<br>
				<p>
    				<strong>
    					<a href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo=<?php echo $codHojaTrabajo;?>">
    						Volver a la hoja de trabajo
    					</a>
    				</strong>
				<p>
			</div>
		</div>
		<?php
    }
    ?>
		
	</section>
	<!-- #contact -->

</main>

<?php
}

function formulario($datos, $errores, $articulos)
{
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
		<!-- Articulo -->
		<div class="form-group">
			<label for="articulo">Artículo</label>
			<select name="articulo" id="articulo" class="form-control">
			<option>- Elige un artículo-</option>
				<?php
				foreach ($articulos as $articulo) {
				    echo "<option value='{$articulo["codArticulo"]}'>{$articulo["nombre"]} ({$articulo["precioVenta"]}€)</option>";
				}
                ?>
			</select>
			<div class="validation"></div>
		</div>

		<!-- Unidades -->
		<div class="form-group">
			<label for="unidades">Unidades</label>
			<input type="number" name="unidades" value="1" min="1" class="form-control">
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="anadir">Añadir</button>
		</div>

	</form>
</div>
<?php
}

