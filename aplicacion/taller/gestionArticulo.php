<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codArticulo = $_GET["codArticulo"];

if ($ACL->existeArticulo($codArticulo)) {
    $articulo = $ACL->getArticulo($codArticulo);
}

$tiposArt = $ACL->dameTiposArticulos();

foreach ($tiposArt as $clave => $valor) {
    if ($clave == $articulo["codTipoArticulo"]) {
        $tipoArt = $valor;
        break;
    }
}

$datos = [
    "codigoReferencia" => "",
    "nombre" => "",
    "proveedor" => "",
    "descripcion" => "",
    "stock" => 0,
    "precioCompra" => 0.0,
    "precioVenta" => 0.0,
    "codTipoArticulo" => 0
];
$errores = [];

$actualizado = false;
$borrado = false;
$deshecho = false;

if ($_POST) {
    if (isset($_POST["borrar"])) {
        if ($ACL->borrarArticulo($codArticulo))
            $borrado = true;
    }
    
    if (isset($_POST["deshacer"])) {
        if ($ACL->actualizarArticulo('borrado', 0, $codArticulo));
            $deshecho = true;
    }

    if (isset($_POST["actualizar"])) {

        // Codigo referencia
        $codRef = "";
        if (isset($_POST["codRef"])) {
            $codRef = mb_strtoupper($_POST["codRef"]);
        }

        if ($codRef != $articulo["codReferencia"]) {

            if (! preg_match('/^[A-Za-z][0-9]{9}$/', $codRef)) {
                $errores["codigoReferencia"] = "Codigo de referencia no válido. Debe tener una letra seguido de nueve números.";
                $codRef = "";
            }

            if ($ACL->existeArticulo($codRef)) {
                $errores["codigoReferencia"] = "Ya existe un artículo con ese código de referencia";
                $codRef = "";
            }

            $datos["codigoReferencia"] = $codRef;
            $ACL->actualizarArticulo("codReferencia", $codRef, $codArticulo);
        }

        // Nombre
        $nombre = "";
        if (isset($_POST["nombre"])) {
            $nombre = $_POST["nombre"];
        }

        if ($nombre != $articulo["nombre"]) {

            if ($nombre == "") {
                $errores = "El nombre no puede estar vacío";
            }

            $datos["nombre"] = $nombre;
            $ACL->actualizarArticulo("nombre", $nombre, $codArticulo);
        }

        // Proveedor
        $proveedor = "";
        if (isset($_POST["proveedor"])) {
            $proveedor = $_POST["proveedor"];
        }

        if ($proveedor != $articulo["proveedor"]) {

            if ($proveedor == "") {
                $errores = "El proveedor no puede estar vacío";
            }

            $datos["proveedor"] = $proveedor;
            $ACL->actualizarArticulo("proveedor", $proveedor, $codArticulo);
        }

        // Descripcion
        $descripcion = "";
        if (isset($_POST["descripcion"])) {
            $descripcion = $_POST["descripcion"];
        }

        if ($descripcion != $articulo["descripcion"]) {
            if (strlen($descripcion) < 25) {
                $errores["descripcion"] = "La descripción debe contener al menos 25 caracteres";
                $descripcion = "";
            }

            $datos["descripcion"] = $descripcion;
            $ACL->actualizarArticulo("descripcion", $descripcion, $codArticulo);
        }

        // Stock
        $stock = 0;
        if (isset($_POST["stock"])) {
            $stock = $_POST["stock"];
        }

        if ($stock != $articulo["stock"]) {

            if ($stock < 0) {
                $errores["stock"] = "El stock debe ser postivo";
                $stock = 0;
            }

            $datos["stock"] = $stock;
            $ACL->actualizarArticulo("stock", $stock, $codArticulo);
        }

        // Precio compra
        $precioCompra = 0.0;
        if (isset($_POST["precioComp"])) {
            $precioCompra = $_POST["precioComp"];
        }

        if ($precioCompra != $articulo["precioCompra"]) {

            if ($precioCompra < 0) {
                $errores["precioCompra"] = "El precio de compra debe ser más de cero";
                $precioCompra = 0.0;
            }

            $datos["precioCompra"] = $precioCompra;
            $ACL->actualizarArticulo("precioCompra", $precioCompra, $codArticulo);
        }

        // Precio venta
        $precioVenta = 0.0;
        if (isset($_POST["precioVent"])) {
            $precioVenta = $_POST["precioVent"];
        }

        if ($precioVenta != $articulo["precioVenta"]) {

            if ($precioVenta < 0) {
                $errores["precioVenta"] = "El precio de venta debe ser más de cero";
                $precioVenta = 0.0;
            }

            if ($precioVenta < $precioCompra) {
                $errores["precioVenta"] = "El precio de venta debe ser superior al precio de compra";
                $precioVenta = 0.0;
            }

            $datos["precioVenta"] = $precioVenta;
            $ACL->actualizarArticulo("precioVenta", $precioVenta, $codArticulo);
        }

        // Tipo articulo
        $tipoArt = "";
        if (isset($_POST["tipoArt"])) {
            $tipoArt = $_POST["tipoArt"];
        }

        if (($codTipoArt = $ACL->getCodigoTipoArticulo($tipoArt)) != false) {

            if ($codTipoArt != $articulo["codTipoArticulo"]) {

                $datos['codTipoArticulo'] = $codTipoArt;
                $ACL->actualizarArticulo("codTipoArticulo", $codTipoArt, $codArticulo);
            }
        }

        if (empty($errores)) {
            $actualizado = true;
        }
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $articulo, $tiposArt, $actualizado, $borrado, $deshecho);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $articulo, $tiposArt, $hecho, $borrado, $deshecho)
{
    ?>
<main id="main">

	<!--==========================
      Register Section
    ============================-->
	<section id="contact" class="section-bg wow fadeInUp">

			<?php
    if ($borrado) {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Borrado!</h2>
				<p>
					Se ha borrado el artículo<br> <strong> <a id="volverAlmacen"
						href="/aplicacion/taller/controlAlmacen.php">Volver al control de
							almacén</a>
					</strong>
				
				
				<p>
			
			</div>
		</div>
		<?php
    }
    else if($deshecho) { 
    ?>
    	<div class="container">
			<div class="section-header">
				<h2>¡Borrado deshecho!</h2>
				<p>Se ha deshecho el borrado del artículo<br> 
					<strong> 
						<a id="volverAlmacen" href="/aplicacion/taller/controlAlmacen.php">
							Volver al control de almacén
						</a>
					</strong>
				<p>
			</div>
		</div>
    
    <?php
    }
    else if (! empty($errores) || ! $hecho) {
        ?>
    	    <div class="container">

			<div class="section-header">
				<h2>Modificar artículo</h2>
				<p>Modificar un artículo ya existente</p>
			</div>			
	    <?php
        formulario($datos, $errores, $articulo, $tiposArt);
        
        
        if($articulo["borrado"] == 0) {
        ?>
        	<hr>
			<div class="form">
				<form action="" method="post" role="form" class="contactForm">
					<div class="text-center">
						<h4>¿Deseas borrar este artículo?</h4>
						<button name="borrar" type="submit">
							<img src="/imagenes/taller/borrarBlanco.png" title="Borrar">
						</button>
					</div>
				</form>
			</div>
		</div>
        <?php
        }
        else {
            ?>
        	<hr>
			<div class="form">
				<form action="" method="post" role="form" class="contactForm">
					<div class="text-center">
						<h4>¿Deseas deshacer el borrado de este artículo?</h4>
						<button name="deshacer" type="submit">
							<img src="/imagenes/taller/undoDelete.png" title="Borrar">
						</button>
					</div>
				</form>
			</div>
		</div>
        <?php
        }
    } else {
        ?><div class="container">

			<div class="section-header">
				<h2>¡Actualizado!</h2>
				<p>
					Se ha actualizado el artículo con éxito<br> <strong> <a
						id="volverAlmacen" href="/aplicacion/taller/controlAlmacen.php">Volver
							al control de almacén</a>
					</strong>
				
				
				<p>
			
			</div>


		</div>
		<?php
    }

    ?>
    
    
		
	</section>

</main>

<?php
}

function formulario($datos, $errores, $articulo, $tiposArt)
{
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
		<!-- Codigo referencia -->
		<div class="form-group">
			<label for="codRef">Código de referencia</label> <input type="text"
				class="form-control" name="codRef" id="codRef" maxlength="10"
				placeholder="Ej: A000000000"
				value="<?php echo $datos["codigoReferencia"]==""?$articulo['codReferencia']:$datos["codigoReferencia"];?>"
				required />
			<div class="validation"></div>
		</div>
		<!-- Nombre -->
		<div class="form-group">
			<label for="nombre">Nombre</label> <input type="text"
				class="form-control" name="nombre" id="nombre" maxlength="30"
				placeholder="Ej: Cable de frenos"
				value="<?php echo $articulo['nombre'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Proveedor -->
		<div class="form-group">
			<label for="proveedor">Proveedor</label> <input type="text"
				class="form-control" name="proveedor" id="proveedor" maxlength="30"
				placeholder="Ej: Shimano"
				value="<?php echo $articulo['proveedor'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Descripcion -->
		<div class="form-group">
			<label for="descripcion">Descripción</label> <input type="text"
				class="form-control" name="descripcion" id="descripcion"
				maxlength="250" placeholder="Ej: "
				value="<?php echo $articulo['descripcion'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Stock -->
		<div class="form-group">
			<label for="stock">Stock</label> <input type="number"
				class="form-control" name="stock" id="stock" min="0"
				placeholder="Ej: 5" value="<?php echo $articulo['stock'];?>"
				required />
			<div class="validation"></div>
		</div>
		<!-- Precio compra -->
		<div class="form-group">
			<label for="precioComp">Precio de compra (€)</label> <input
				type="number" class="form-control" name="precioComp" id="precioComp"
				step='0.01' placeholder="Ej: 0.00"
				value="<?php echo $articulo['precioCompra'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Precio venta -->
		<div class="form-group">
			<label for="precioVent">Precio de venta (€)</label> <input
				type="number" class="form-control" name="precioVent" id="precioVent"
				step='0.01' placeholder="Ej: 0.00"
				value="<?php echo $articulo['precioVenta'];?>" required />
			<div class="validation"></div>
		</div>

		<!-- Tipo de articulo -->
		<div class="form-group">
			<label for="tipoArt">Tipo de artículo</label>
			<br> 
			<select name="tipoArt" id="tipoArt" class="form-control">
				<?php
                foreach ($tiposArt as $clave => $valor) {
                    echo "<option name='{$clave}'";
                    if ($clave == $articulo["codTipoArticulo"])
                        echo " selected='selected'";
                    echo ">{$valor}</option>";
                }
                ?>
			</select>
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="actualizar">Actualizar</button>
		</div>

	</form>
</div>
<?php
}


