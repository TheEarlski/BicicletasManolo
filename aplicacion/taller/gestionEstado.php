<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codEstado = $_GET["codEstado"];
$estado = $ACL->getEstado($codEstado);
$codHojaTrabajo = $estado["codHojaTrabajo"];

$datos = [
    "estado"=>"",
    "fecha"=>""
];
$errores = [];

$actualizado = false;
$borrado = false;

if ($_POST) {
    if (isset($_POST["borrar"])) {
        if ($ACL->borrarEstado($codEstado))
            $borrado = true;
    }
    
    if (isset($_POST["actualizar"])) {
        
        //Estado
        $estado = "";
        if(isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        }
        
        if($estado == "") {
            $errores["estado"] = "El estado no debe estar vacio";
        }
        
        $datos["estado"] = $estado;
        $ACL->actualizarEstado('estado', $estado, $codEstado);
        
        //Fecha
        $fecha = "";
        if(isset($_POST["fecha"])) {
            $fecha = $_POST["fecha"];
        }
        
        if($fecha == "") {
            $errores["fecha"] = "La fecha no debe estar vacia";
        }
        
        $datos["fecha"] = $fecha;
        $ACL->actualizarEstado('fecha', $fecha, $codEstado);
        
        if (empty($errores)) {
            $actualizado = true;
        }
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $actualizado, $borrado, $estado, $codHojaTrabajo);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
	<?php
}

function cuerpo($datos, $errores, $hecho, $borrado, $estado, $codHojaTrabajo)
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
				<p>Se ha borrado el estado<br> 
    				<strong><a id="volverAlmacen" href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo=<?php echo $estado["codHojaTrabajo"];?>">
    					Volver a la hoja de trabajo
    				</a></strong>
				<p>
			</div>
			
		</div>
		<?php
    } else if (! empty($errores) || ! $hecho) {
        ?>
    	    <div class="container">

			<div class="section-header">
				<h2>Modificar estado</h2>
				<p>Modificar un estado</p>
			</div>			
	    <?php
        formulario($datos, $errores, $estado);
        ?>
        	<hr>
			<div class="form">
				<form action="" method="post" role="form" class="contactForm">
					<div class="text-center">
						<h4>¿Deseas borrar este estado?</h4>
						<button name="borrar" type="submit">
							<img src="/imagenes/taller/borrarBlanco.png" title="Borrar">
						</button>
					</div>
				</form>
			</div>
		</div>
        <?php
    } else {
        ?><div class="container">

			<div class="section-header">
				<h2>¡Actualizado!</h2>
				<p>Se ha actualizado el estado con éxito<br> 
					<strong> <a id="volverAlmacen" href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo=<?php echo $codHojaTrabajo; ?>">
						Volver a la hoja de trabajo
					</a> </strong>
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

function formulario($datos, $errores, $estado)
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
		<!-- Estado -->
		<div class="form-group">
			<label for="estado">Estado</label> <input type="text"
				class="form-control" name="estado" id="estado" maxlength="250"
				value="<?php echo $datos['estado']==""?$estado['estado']:$datos['estado'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Fecha -->
		<div class="form-group">
			<label for="fecha">Fecha</label> <input type="date"
				class="form-control" name="fecha" id="fecha" maxlength="30"
				value="<?php echo $estado['fecha'];?>" required />
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="actualizar">Actualizar</button>
		</div>

	</form>
</div>
<?php
}


