<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "modelo"=>"",
    "descripcion"=>"",
    "tamanioRuedas"=>0.0,
    "marchas"=>0,
    "correo"=>"",
    "peso"=>0.0,
    "material"=>"",
    "codTipoBici"=>"",
    "codCliente"=>0
];
$errores = [];

$tiposBicis = $ACL->dameTiposBicis();

$actualizado = false;

if ($_POST) {
    if(isset($_POST["anadir"])) {
        
        //Modelo
        $codRef = "";
        if(isset($_POST["modelo"])) {
            $codRef = mb_strtoupper($_POST["modelo"]);
        }
        
        if($codRef == "") {
            $errores = "El modelo no puede estar vacío";
        }
        
        $datos["modelo"] = $codRef;
        
        //Descripcion
        $descrip = "";
        if(isset($_POST["descripcion"])) {
            $descrip = $_POST["descripcion"];
        }
        
        if($descrip == "") {
            $errores = "La descripción no puede estar vacía";
        }
        
        $datos["descripcion"] = $descrip;
        
        //Tamaño ruedas
        $tamRue = "";
        if(isset($_POST["tamañoRuedas"])) {
            $tamRue = floatval($_POST["tamañoRuedas"]);
        }
        
        $datos["tamanioRuedas"] = $tamRue;
        
        //Marchas
        $marchas = "";
        if(isset($_POST["marchas"])) {
            $marchas = intval($_POST["marchas"]);
        }
        
        $datos["marchas"] = $marchas;
        
        //Correo
        $correo = "";
        if (isset($_POST["correo"])) {
            $correo = $_POST["correo"];
        }
        
        $correo = mb_strtoupper($correo);
        
        if (! preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $correo)) {
            $errores["Correo"] = "Correo no válido";
            $correo = "";
        }
        
        if(($codCliente = $ACL->getCodigoCliente($correo)) != false) {
            $datos["codCliente"] = $codCliente;
        }
        $datos["correo"] = $correo;
        
        //Peso
        $peso = 0.0;
        if(isset($_POST["peso"])) {
            $peso = floatval($_POST["peso"]);
        }
        
        $datos["peso"] = $peso;
        
        //Problema
        $material = "";
        if(isset($_POST["material"])) {
            $material = $_POST["material"];
        }
        
        $datos["material"] = $material;
        
        //Tipo bici
        $tipoBici = "";
        if(isset($_POST["tipoBici"])) {
            $tipoBici = $_POST["tipoBici"];
        }
        
        if($tipoBici == "--Elige un tipo--") {
            $errores["tipoBici"] = "Debe elegir un tipo de bicicleta";
            $tipoBici = "";
        }

        if (($codTipoBici = $ACL->getCodigoTipoBicicleta($tipoBici)) != false) {
            $datos["codTipoBici"] = $codTipoBici;
        }
        
        if (empty($errores)) {
            $ACL->anadirBicicleta($datos["modelo"], $datos["descripcion"], $datos["marchas"], $datos["tamanioRuedas"], $datos["peso"], $datos["material"], $datos["codTipoBici"], $datos["codCliente"]);
            $actualizado = true;
        }
        
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $tiposBicis, $actualizado);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $tiposBicis, $hecho)
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
				<h2>Añadir Bicicleta</h2>
				<p>Añadir una bicicleta nueva</p>
			</div>
    			    <?php
        formulario($datos, $errores, $tiposBicis);
        ?>
			</div>
        <?php
    } else {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Añadido!</h2>
				<p>Se ha añadido la bicicleta con éxito</p>
				<br>
				<p><a href="/aplicacion/taller/bicicletas.php">Volver a gestión de bicicletas</a><p>
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

function formulario($datos, $errores, $tiposBicis)
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
		<!-- Modelo -->
		<div class="form-group">
			<label for="modelo">Modelo</label> <input type="text"
				class="form-control" name="modelo" id="modelo" maxlength="50"
				placeholder="Ej: Scott Addict RC" value="<?php echo $datos['modelo'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Descripcion -->
		<div class="form-group">
			<label for="descripcion">Descripción</label> <input type="text"
				class="form-control" name="descripcion" id="desripcion" maxlength="250"
				placeholder="Ej: Una bicicleta ligera de carretera de colores rojo y negro..." 
				 value="<?php echo $datos['descripcion'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Correo -->
		<div class="form-group">
			<label for="correo">Correo electrónico del cliente</label> <input type="text"
				class="form-control" name="correo" id="correo" maxlength="50"
				placeholder="Ej: patata@gmail.com" 
				 value="<?php echo $datos['correo'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Tamaño ruedas -->
		<div class="form-group">
			<label for="tamañoRuedas">Tamaño de ruedas (pulgadas ")</label> <input type="number"
				class="form-control" name="tamañoRuedas" id="tamañoRuedas" step='0.1'
				placeholder="Ej: 16" value="<?php echo $datos['tamanioRuedas'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Marchas -->
		<div class="form-group">
			<label for="marchas">Marchas</label> <input type="number"
				class="form-control" name="marchas" id="marchas" min="0"
				placeholder="Ej: 15" value="<?php echo $datos['marchas'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Peso -->
		<div class="form-group">
			<label for="peso">Peso (kg)</label> <input type="number"
				class="form-control" name="peso" id="peso" step='0.01'
				placeholder="Ej: 10.50" value="<?php echo $datos['peso'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Material -->
		<div class="form-group">
			<label for="material">Material</label> <input type="text"
				class="form-control" name="material" id="material" maxlength="30"
				placeholder="Ej: Alumninio" 
				 value="<?php echo $datos['material'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Tipo de bicicleta -->
		<div class="form-group">
			<label for="tipoArt">Tipo de bicicleta</label><br>
			<select name="tipoBici" id="tipoBici" class="form-control">
				<option>--Elige un tipo--</option>
				<?php 
				    foreach ($tiposBicis as $clave=>$valor) {
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

