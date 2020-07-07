<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "codigoReferencia"=>"",
    "nombre"=>"",
    "proveedor"=>"",
    "descripcion"=>"",
    "stock"=>0,
    "precioCompra"=>0.0,
    "precioVenta"=>0.0,
    "codTipoArticulo"=>0
];
$errores = [];

$tipoArt = $ACL->dameTiposArticulos();

$actualizado = false;

if ($_POST) {
    if(isset($_POST["anadir"])) {
        
        //Codigo referencia
        $codRef = "";
        if(isset($_POST["codRef"])) {
            $codRef = mb_strtoupper($_POST["codRef"]);
        }
        
        if(!preg_match('/^[A-Za-z][0-9]{9}$/', $codRef)) {
            $errores["codigoReferencia"] = "Codigo de referencia no válido. Debe tener una letra seguido de nueve números.";
            $codRef = "";
        }
        
        if($ACL->existeArticulo($codRef)) {
            $errores["codigoReferencia"] = "Ya existe un artículo con ese código de referencia";
            $codRef = "";
        }
        
        $datos["codigoReferencia"] = $codRef;
        
        //Nombre
        $nombre = "";
        if(isset($_POST["nombre"])) {
            $nombre = $_POST["nombre"];
        }
        
        if($nombre == "") {
            $errores = "El nombre no puede estar vacío";
        }
        
        $datos["nombre"] = $nombre;
        
        //Proveedor
        $proveedor = "";
        if(isset($_POST["proveedor"])) {
            $proveedor = $_POST["proveedor"];
        }
        
        if($proveedor == "") {
            $errores = "El proveedor no puede estar vacío";
        }
        
        $datos["proveedor"] = $proveedor;
        
        //Descripcion
        $descripcion = "";
        if(isset($_POST["descripcion"])) {
            $descripcion = $_POST["descripcion"];
        }
        
        if(strlen($descripcion) < 25) {
            $errores["descripcion"] = "La descripción debe contener al menos 25 caracteres";
            $descripcion = "";
        }
        
        $datos["descripcion"] = $descripcion;
        
        //Stock
        $stock = 0;
        if(isset($_POST["stock"])) {
            $stock = $_POST["stock"];
        }
        
        if($stock < 0) {
            $errores["stock"] = "El stock debe ser postivo";
            $stock = 0;
        }
        
        $datos["stock"] = $stock;
        
        //Precio compra
        $precioCompra = 0.0;
        if(isset($_POST["precioComp"])) {
            $precioCompra = $_POST["precioComp"];
        }
        
        if($precioCompra < 0) {
            $errores["precioCompra"] = "El precio de compra debe ser más de cero";
            $precioCompra = 0.0;
        }
        
        $datos["precioCompra"] = $precioCompra;
        
        //Precio venta
        $precioVenta = 0.0;
        if(isset($_POST["precioVent"])) {
            $precioVenta = $_POST["precioVent"];
        }
        
        if($precioVenta < 0) {
            $errores["precioVenta"] = "El precio de venta debe ser más de cero";
            $precioVenta = 0.0;
        }
        
        if($precioVenta < $precioCompra) {
            $errores["precioVenta"] = "El precio de venta debe ser superior al precio de compra";
            $precioVenta = 0.0;
        }
        
        $datos["precioVenta"] = $precioVenta;
        
        //Tipo articulo
        $tipoArt = "";
        if(isset($_POST["tipoArt"])) {
            $tipoArt = $_POST["tipoArt"];
        }
        
        if($tipoArt == "--Elige un tipo--") {
            $errores["codTipoArticulo"] = "Debe elegir un tipo de articulo";
            $tipoArt = "";
        }
        
        if(($codTipoArt = $ACL->getCodigoTipoArticulo($tipoArt)) != false) {
            $datos['codTipoArticulo'] = $codTipoArt;
        }
        
        if (empty($errores)) {
            if(!$ACL->anadirArticulo($datos["codigoReferencia"], $datos["nombre"], $datos["proveedor"], $datos["descripcion"], $datos["stock"], $datos["precioCompra"], $datos["precioVenta"], $datos["codTipoArticulo"]))
                $actualizado = false;
            else
                $actualizado = true;
        }
        
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $tipoArt, $actualizado);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $tipoArt, $hecho)
{
    ?>
<main id="main">

	<!--==========================
      Register Section
    ============================-->
	<section id="contact" class="section-bg wow fadeInUp">

			<?php
    if (! empty($errores) || ! $hecho) {
        ?>
    	    <div class="container">

			<div class="section-header">
				<h2>Añadir artículo</h2>
				<p>Añadir un artículo nuevo</p>
			</div>
    			    <?php
        formulario($datos, $errores, $tipoArt);
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
				<p><a href="/aplicacion/taller/controlAlmacen.php">Volver al control de almacén</a><p>
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

function formulario($datos, $errores, $tipoArt)
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
				placeholder="Ej: A000000000" value="<?php echo $datos['codigoReferencia'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Nombre -->
		<div class="form-group">
			<label for="nombre">Nombre</label> <input type="text"
				class="form-control" name="nombre" id="nombre" maxlength="30"
				placeholder="Ej: Cable de frenos" value="<?php echo $datos['nombre'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Proveedor -->
		<div class="form-group">
			<label for="proveedor">Proveedor</label> <input type="text"
				class="form-control" name="proveedor" id="proveedor" maxlength="30"
				placeholder="Ej: Shimano" value="<?php echo $datos['proveedor'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Descripcion -->
		<div class="form-group">
			<label for="descripcion">Descripción</label> <input type="text"
				class="form-control" name="descripcion" id="descripcion"
				maxlength="250" placeholder="Ej: " value="<?php echo $datos['descripcion'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Stock -->
		<div class="form-group">
			<label for="stock">Stock</label> <input type="number"
				class="form-control" name="stock" id="stock" min="0"
				placeholder="Ej: 5" value="<?php echo $datos['stock'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Precio compra -->
		<div class="form-group">
			<label for="precioComp">Precio de compra (€)</label> <input type="number"
				class="form-control" name="precioComp" id="precioComp" step='0.01'
				placeholder="Ej: 0.00" value="<?php echo $datos['precioCompra'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Precio venta -->
		<div class="form-group">
			<label for="precioVent">Precio de venta (€)</label> <input type="number"
				class="form-control" name="precioVent" id="precioVent" step='0.01'
				placeholder="Ej: 0.00" value="<?php echo $datos['precioVenta'];?>" required />
			<div class="validation"></div>
		</div>
		
		<!-- Tipo de articulo -->
		<div class="form-group">
			<label for="tipoArt">Tipo de artículo</label><br>
			<select name="tipoArt" id="tipoArt" class="form-control">
				<option>--Elige un tipo--</option>
				<?php 
				    foreach ($tipoArt as $clave=>$valor) {
				        echo "<option name='{$clave}'>{$valor}</option>";
				    }
				?>
			</select>
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="anadir">Añadir</button>
		</div>

	</form>
</div>
<?php
}

