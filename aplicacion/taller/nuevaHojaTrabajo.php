<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "codCliente"=>0,
    "codBici"=>0,
    "problema"=>""
];
$errores = [];

if(isset($_SESSION["codCliente"])) {
    $datos["codCliente"] = $_SESSION["codCliente"];
}

if(isset($_SESSION["codBici"])) {
    $datos["codBici"] = $_SESSION["codBici"];
}

$clientes = $ACL->dameClientes();

$clienteElegido = false;
$biciElegido = false;
$hecho = false;

if ($_POST) {
    
    if(isset($_POST["clienteElegido"])) {
        $codCliente = 0;
        if(isset($_POST["cliente"])) {
            $codCliente = intval($_POST["cliente"]);
        }
        
        if($codCliente == "") {
            $errores["Cliente"] = "No se ha elegido un cliente";
            $codCliente = 0;
        }
        
        $datos["codCliente"] = $codCliente;
        $_SESSION["codCliente"] = $codCliente;
        $clienteElegido = true;
    }
    
    if(isset($_POST["biciElegido"])) {
        $codBici = 0;
        if(isset($_POST["bici"])) {
            $codBici = intval($_POST["bici"]);
        }
        
        if($codBici == "") {
            $errores["Bicicleta"] = "No se ha elegido una bicicleta";
            $codBici = 0;
        }
        
        $datos["codBici"] = $codBici;
        $_SESSION["codBici"] = $codBici;
        $clienteElegido = true;
        $biciElegido = true;
    }
    
    if(isset($_POST["añadir"])) {
        
        $problema = "";
        if(isset($_POST["problema"])) {
            $problema = $_POST["problema"];
        }
        
        if($problema == "") {
            $errores["Problema"] = "Se debe especifcar algun problema";
        }
        
        $datos["problema"] = $problema;
        
        if(empty($errores)) {
            $ACL->anadirHojaTrabajo($datos["codBici"], $datos["problema"]);
            unset($_SESSION["codCliente"]);
            unset($_SESSION["codBici"]);
            $hecho = true;
        }
    }
 
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $clientes, $clienteElegido, $biciElegido, $hecho, $ACL);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $clientes, $clienteElegido, $biciElegido, $hecho, $ACL)
{
    ?>
<main id="main">

	<!--==========================
      Register Section
    ============================-->
	<section id="contact" class="section-bg wow fadeInUp">

		<?php
		if ((!empty($errores) || !$clienteElegido) && !$hecho) {
        ?>
    	    <div class="container">

			<div class="section-header">
				<h2>Añadir hoja de trabajo</h2>
				<p>Añadir una hoja de trabajo nueva</p>
			</div>
	    <?php
	    formularioCliente($datos, $errores, $clientes);
        ?>
			</div>
        <?php
    }
    else if ((!empty($errores) || !$biciElegido) && !$hecho){
        ?>
    	    <div class="container">

			<div class="section-header">
				<h2>Añadir hoja de trabajo</h2>
				<p>Añadir una hoja de trabajo nueva</p>
			</div>
			
			<div class="text-center">
				<p>Cliente: <br><?php echo $ACL->getCliente($datos["codCliente"])["nombre"]." ".$ACL->getCliente($datos["codCliente"])["apellidos"]?></p>
			</div>
	    <?php
	    $bicisCliente = $ACL->getBicisCliente($datos["codCliente"]);
	    formularioBici($datos, $errores, $bicisCliente);
        ?>
			</div>
        <?php
    }
    else if(($clienteElegido && $biciElegido) && !$hecho) {
            $cliente = $ACL->getCliente($datos["codCliente"]);
        ?>
        <div class="container">
        
        <div class="section-header">
            <h2>Añadir hoja de trabajo</h2>
            <p>Añadir una hoja de trabajo nueva</p>
        </div>
        
        <div class="text-center">
			<p>Cliente: <br><?php echo $cliente["nombre"]." ".$cliente["apellidos"] ?></p>
			<p>Bicicleta: <br><?php echo $ACL->getBicicleta($datos["codBici"])["modelo"];?></p>
		</div>
        
        <?php 
        formularioProblema($datos, $errores);
        ?>
        
        </div>
        <?php
    }
    else {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Añadido!</h2>
				<p>Se ha añadido la hoja de trabajo con éxito</p>
				<br>
				<p><a href="/aplicacion/taller/hojasTrabajo.php">Volver a las hojas de trabajo</a><p>
			</div>
		</div>
		<?php
    }
    ?>
		
	</section>

</main>

<?php
}

function formularioCliente($datos, $errores, $clientes)
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
		
		<!-- Clientes -->
		<div class="form-group">
			<label for="cliente">Cliente</label><br>
			<select name="cliente" id="cliente" class="form-control">
				<option>- Elige el cliente -</option>
				<?php 
				    foreach ($clientes as $cliente) {
				        echo "<option value='{$cliente["codCliente"]}'>".$cliente["nombre"]." ".$cliente["apellidos"]."</option>";
				    }
				?>
			</select>
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="clienteElegido">Elegir</button>
		</div>

	</form>
</div>
<?php
}

function formularioBici($datos, $errores, $bicis)
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
		
		<!-- Clientes -->
		<div class="form-group">
			<label for="bici">Bicicleta</label><br>
			<select name="bici" id="bici" class="form-control">
				<option>- Elige la bicicleta del cliente -</option>
				<?php 
				    foreach ($bicis as $bici) {
				        echo "<option value='{$bici["codBici"]}'>".$bici["modelo"]."</option>";
				    }
				?>
			</select>
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="biciElegido">Elegir</button>
		</div>

	</form>
</div>
<?php
}

function formularioProblema($datos, $errores)
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
		
		<!-- Clientes -->
		<div class="form-group">
			<label for="problema">Problema</label><br>
			<input type="text" name="problema" id="problema" class="form-control" maxlength="50"
				placeholder="Ej: Ambas ruedas pinchadas"/>
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="añadir">Añadir</button>
		</div>

	</form>
</div>
<?php
}