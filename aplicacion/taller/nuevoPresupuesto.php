<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "codCliente"=>0,
    "codBici"=>0,
    "descripcion"=>"",
    "manoObra"=>0
];
$errores = [];

if(isset($_SESSION["codCliente"])) {
    $datos["codCliente"] = $_SESSION["codCliente"];
}

if(isset($_SESSION["codBici"])) {
    $datos["codBici"] = $_SESSION["codBici"];
}

if(isset($_SESSION["descripcion"])) {
    $datos["descripcion"] = $_SESSION["descripcion"];
}

$clientes = $ACL->dameClientes();

$clienteElegido = false;
$biciElegido = false;
$descripcionEscrito = false;
$componentesElegidos = false;
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
    
    if(isset($_POST["descripcionEscrito"])) {
        
        $descripcion = "";
        if(isset($_POST["descripcion"])) {
            $descripcion = $_POST["descripcion"];
        }
        
        if($descripcion == "") {
            $errores["descripcion"] = "Se debe especifcar una descripción";
        }
        
        $datos["descripcion"] = $descripcion;
        $_SESSION["descripcion"] = $descripcion;
        $clienteElegido = true;
        $biciElegido = true;
        $descripcionEscrito = true;
    }
    
    if(isset($_POST["añadir"])) {
        
        //Mano de obra
        $manoObra=0;
        if(isset($_POST["manoObra"])) {
            $manoObra = intval($_POST["manoObra"]);
        }
        
        if($manoObra == "- Elige el tiempo estimado -") {
            $errores["manoObra"] = "Debe elegir algun tiempo de mano de obra";
            $manoObra = 0;
        }
        
        $datos["manoObra"] = $manoObra;
        
        for($i=0; $i<(count($_POST)-2)/2; $i++) {
            //Componentes
            $componente = 0;
            if(isset($_POST["componente".$i])) {
                $componente = intval($_POST["componente".$i]);
            }
            
            if($componente == "- Elige un componente -") {
                continue;
            }
            
            $datos["componente".$i] = $componente;
            
            //Unidades
            $unidades = 0;
            if(isset($_POST["unidades".$i])) {
                $unidades = intval($_POST["unidades".$i]);
            }
            
            if($unidades <= 0) {
                $errores["unidades"] = "Las unidades de un componente no pueden ser inferior a 1";
            }
            
            $datos["unidades".$i] = $unidades;
            
        }
        $componentesElegidos = true;
    }
        
    if(empty($errores) && $componentesElegidos) {
        $codPresupuesto = intval($ACL->anadirPresupuesto($datos["codBici"], $datos["descripcion"]));
        $ACL->anadirArticuloPresupuesto($datos["manoObra"], $codPresupuesto, 1, $ACL->getArticulo($datos["manoObra"])["precioVenta"]);
        
        $longitud = count($datos)-4;
        for($i = 0; $i<$longitud; $i++) {
            if(isset($datos["componente".$i])) {
                $ACL->anadirArticuloPresupuesto($datos["componente".$i], $codPresupuesto, $datos["unidades".$i], $ACL->getArticulo($datos["componente".$i])["precioVenta"]);
            }
        }
        $cliente = $ACL->getCliente($datos["codCliente"]);
        $mensaje = "Hola ".mb_convert_case($cliente["nombre"], MB_CASE_TITLE, "UTF-8")."\nLe enviamos este correo para avisarle que se ha creado un nuevo presupuesto para una bicicleta suya.".
            "\nPara ver los detalles del presupuesto puede dirigirse a esta página http://www.bicicletasmanolo.es/aplicacion/taller/misPresupuestos.php.".
            "\n\n¡Gracias por su confianza en Bicicletas Manolo!";
        Utilidades::enviarCorreo($cliente["correo"], $cliente["nombre"], 'Se ha creado un nuevo presupuesto', $mensaje);
        
        unset($_SESSION["codCliente"]);
        unset($_SESSION["codBici"]);
        unset($_SESSION["descripcion"]);
        $hecho = true;
    }
    
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $clientes, $clienteElegido, $biciElegido, $descripcionEscrito, $hecho, $ACL);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
	<script src="/javascript/taller/nuevoPresupuesto.js"></script>
    <?php
}

function cuerpo($datos, $errores, $clientes, $clienteElegido, $biciElegido, $descripcionEscrito, $hecho, $ACL)
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
				<h2>Crear presupuesto</h2>
				<p>Crear un nuevo presupuesto</p>
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
				<h2>Crear presupuesto</h2>
				<p>Crear un nuevo presupuesto</p>
			</div>
			
			<div class="text-center">
				<p>Cliente: <br><?php echo $clientes[$datos["codCliente"]-1]["nombre"]." ".$clientes[$datos["codCliente"]-1]["apellidos"]?></p>
			</div>
	    <?php
	    $bicisCliente = $ACL->getBicisCliente($datos["codCliente"]);
	    formularioBici($datos, $errores, $bicisCliente);
        ?>
			</div>
        <?php
    }
    else if($clienteElegido && $biciElegido && !$descripcionEscrito && !$hecho) {
            $cliente = $ACL->getCliente($datos["codCliente"]);
        ?>
        <div class="container">
        
        <div class="section-header">
            <h2>Crear presupuesto</h2>
			<p>Crear un nuevo presupuesto</p>
        </div>
        
        <div class="text-center">
			<p>Cliente: <br><?php echo $cliente["nombre"]." ".$cliente["apellidos"] ?></p>
			<p>Bicicleta: <br><?php echo $ACL->getBicicleta($datos["codBici"])["modelo"];?></p>
		</div>
        
        <?php 
        formularioDescripcion($datos, $errores);
        ?>
        
        </div>
        <?php
    }
    else if (($clienteElegido && $biciElegido && $descripcionEscrito) && !$hecho) {
        $cliente = $ACL->getCliente($datos["codCliente"]);
    ?>
    	<div class="container">
        
        <div class="section-header">
            <h2>Crear presupuesto</h2>
			<p>Crear un nuevo presupuesto</p>
        </div>
        
        <div class="text-center">
			<p>Cliente: <br><?php echo $cliente["nombre"]." ".$cliente["apellidos"] ?></p>
			<p>Bicicleta: <br><?php echo $ACL->getBicicleta($datos["codBici"])["modelo"];?></p>
			<p>Descripcion: <br><?php echo $datos["descripcion"];?></p>
		</div>
        
        <?php 
        formularioTiempo($datos, $errores, $ACL);
        ?>
        
        </div>
    <?php
    }
    else{
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Presupuesto creado!</h2>
				<p>Se ha creado un presupuesto con éxito</p>
				<br>
				<p><a href="/aplicacion/taller/presupuestos.php"><strong>Volver a los presupuestos</strong></a><p>
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
		
		<!-- Bicicleta -->
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

function formularioDescripcion($datos, $errores)
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
		
		<!-- Descripcion -->
		<div class="form-group">
			<label for="descripcion">Descripción</label><br>
			<input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="50"
				autocomplete="off" placeholder="Ej: Ambas ruedas pinchadas"/>
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="descripcionEscrito">Añadir</button>
		</div>

	</form>
</div>
<?php
}

function formularioTiempo($datos, $errores, $ACL)
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
	<div>
		<p>* son campos obligatorios</p>
	</div>
	<form action="" method="post" role="form" class="contactForm" id="formComp">
		
		<!-- Tiempo -->
		<div class="form-group">
			<label for="manoObra">Tiempo de mano de obra*</label><br>
			<select name="manoObra" id="manoObra" class="form-control">
				<option>- Elige el tiempo estimado -</option>
				<?php 
				$horas = $ACL->dameHorasTrabajo();
				    foreach ($horas as $hora) {
				        echo "<option value='{$hora["codArticulo"]}'>{$hora["nombre"]} ({$hora["precioVenta"]}€)</option>";
				    }
				?>
			</select>
		</div>
		
		<!-- Componente -->
		<div class="form-group">
    		<div class="componente">
    			<div>
        			<label for="componente0">Componente</label><br>
        			<select name="componente0" id="componente0" class="form-control">
        				<option>- Elige un componente -</option>
        				<?php 
        				$componentes = $ACL->dameComponentes();
        				foreach ($componentes as $componente) {
    				        echo "<option value='{$componente["codArticulo"]}'>{$componente["nombre"]} ({$componente["precioVenta"]}€)</option>";
    				    }
        				?>
        			</select>
    			</div>
    			
    			<div class="unidades">
        			<label for="unidades0">Unidades</label>
        			<input type="number" name="unidades0" id="unidades0" class="form-control" min="1"  value="1"/>
    			</div>
			</div>
		</div>
		
		<div class="text-center" id="crearPresu">
			<button type="submit" name="añadir">Añadir</button>
		</div>
	</form>
	
	<button id="añadirComp" name="añadirComp">
		<img id="imgAñadirComp" src="/imagenes/taller/plus.png" alt="Cruz de añadir"> Añadir componente
	</button>
	
</div>
<?php
}