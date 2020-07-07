<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "nombre" => "",
    "apellidos" => "",
    "dni" => "",
    "correo" => "",
    "correoConf"=>"",
    "contra" => "",
    "direccion" => "",
    "telefono" => ""
];

$errores = [];
$actualizado = false;

if ($_POST) {

    if (isset($_POST["registrar"])) {

        // NOMBRE
        $descrip = "";
        if (isset($_POST["nombre"])) {
            $descrip = $_POST["nombre"];
        }

        $descrip = mb_strtoupper($descrip);

        if (mb_strlen($descrip) <= 2) {
            $errores["Nombre"] = "Su nombre debe contener más de 2 caracteres";
            $descrip = "";
        }

        $datos["nombre"] = $descrip;

        // APELLIDOS
        $ape = "";
        if (isset($_POST["apellidos"])) {
            $ape = $_POST["apellidos"];
        }

        $ape = mb_strtoupper($ape);

        if (mb_strlen($ape) < 3) {
            $errores["Apellidos"] = "Sus apellidos debe contener más de 5 caracteres";
            $ape = "";
        }

        $datos["apellidos"] = $ape;

        // DNI
        $dni = "";
        if (isset($_POST["dni"])) {
            $dni = $_POST["dni"];
        }

        $dni = mb_strtoupper($dni);

        if (! preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            $errores["DNI"] = "DNI o CIF no válido. Debe seguir esta formato 00000000A";
            $dni = "";
        }

        $datos["dni"] = $dni;

        // CORREO
        $correo = "";
        if (isset($_POST["correo"])) {
            $correo = $_POST["correo"];
        }

        $correo = mb_strtoupper($correo);

        if (! preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $correo)) {
            $errores["Correo"] = "Correo no válido";
            $correo = "";
        }

        $datos["correo"] = $correo;

        // CONFIRMAR CORREO
        $confCorreo = "";

        if (isset($_POST["confirmarCorreo"])) {
            $confCorreo = $_POST["confirmarCorreo"];
        }

        $confCorreo = mb_strtoupper($confCorreo);

        if ($confCorreo != $correo) {
            $errores["confCorreo"] = "Los correos no son iguales";
            $confCorreo = "";
        }
        
        $datos["confCorreo"] = $confCorreo;

        // CONSTRASEÑA
        $contra = "";

        if (isset($_POST["contrasenia"])) {
            $contra = md5($_POST["contrasenia"]);
        }

        $datos["contra"] = $contra;

        // CONFIRMAR CONTRASEÑA
        $confContra = "";
        if (isset($_POST["confirmarContrasenia"])) {
            $confContra = md5($_POST["confirmarContrasenia"]);
        }

        if ($contra != $confContra) {
            $errores["Contraseña"] = "Las contraseñas no coinciden";
            $confContra = "";
        }

        // DIRECCION
        $dir = "";
        if (isset($_POST["direccion"])) {
            $dir = $_POST["direccion"];
        }

        $dir = mb_strtoupper($dir);

        if ($dir == "" | mb_strlen($dir) < 10) {
            $errores["Direccion"] = "Direccion no valida";
            $dir = "";
        }

        $datos["direccion"] = $dir;

        // TELEFONO
        $tel = "";
        if (isset($_POST["telefono"])) {
            $tel = $_POST["telefono"];
        }

        if (mb_strlen($tel) == 9 && preg_match("/^[67][0-9]{8}$/", $tel)) {
            $tel = "+34" . $tel;
        }

        if ((strlen($tel)<9)  && !preg_match("/^\+[0-9]{10,11}$/", $tel)) {
            $errores["Telefono"] = "Teléfono no valido. Asegura no poner espacios";
            $tel = "";
        }

        $datos["telefono"] = $tel;

        if (empty($errores)) {
            $ACL->anadirCliente($datos["dni"], $datos["nombre"], $datos["apellidos"], $datos["correo"], $datos["contra"], $datos["direccion"], $datos["telefono"], $ACL->getCodRol("usuarioRegistrado"));
            $mensaje = "Hola {$datos["nombre"]} {$datos["apellidos"]}, \n\nBicicletas Manolo le damos la bienvenida.\nEstamos a su disposición para cualquier cosa sobre bicicletas.".
                "\n\nSi tienes algun problema, no dudes en contactar con nosotros atraves del servicio de mensaje en la página del taller.\n\nGracias por confiar en Bicicletas Manolo.";
            Utilidades::enviarCorreo($datos["correo"], $datos["nombre"], 'Bienvenido a Bicicletas Manolo', $mensaje);
            $actualizado = true;
        }
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $actualizado);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
<link href="../../estilos/loginStyle.css" rel="stylesheet">
<?php
}

function cuerpo($datos, $errores, $hecho)
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
				<h2>Registrar</h2>
				<p>Crear una cuenta nueva</p>
			</div>
			    <?php
        formulario($datos, $errores);
        ?>
        <hr>
			<div class="form">
				<div class="text-center">
					<h4>¿Ya tienes una cuenta?</h4>
					<button type="submit" onclick="window.location.href='./login.php';">Inicia
						sesión</button>
				</div>
			</div>
		</div>
        <?php
    } else {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Registrado!</h2>
				<p>Se ha registrado con éxito</p>
			</div>
			<hr>
			<div class="form">
				<div class="text-center">
					<h4>Entra a su nueva cuenta</h4>
					<button type="submit" onclick="window.location.href='./login.php';">Inicia
						sesión</button>
				</div>
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

function formulario($datos, $errores)
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
		<!-- Nombre -->
		<div class="form-group">
			<label for="nombre">Nombre</label> <input type="text"
				class="form-control" name="nombre" id="nombre" maxlength="30"
				placeholder="Ej: Juan" <?php echo 'value="'.$datos["nombre"].'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- Apellidos -->
		<div class="form-group">
			<label for="apellidos">Apellidos</label> <input type="text"
				class="form-control" name="apellidos" id="apellidos" maxlength="50"
				placeholder="Ej: Rodríguez Sanchez" <?php echo 'value="'.$datos["apellidos"].'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- DNICIF -->
		<div class="form-group">
			<label for="dni">DNI o CIF</label> <input type="text"
				class="form-control" name="dni" id="dni" maxlength="9"
				placeholder="Ej: 73457834V" <?php echo 'value="'.$datos["dni"].'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- Correo -->
		<div class="form-group">
			<label for="correo">Correo</label> <input type="email"
				class="form-control" name="correo" id="correo" maxlength="50"
				placeholder="Ej: ejemplo@ejemplo.es" <?php echo 'value="'.$datos["correo"].'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- Confirmar Correo -->
		<div class="form-group">
			<label for="confirmarCorreo">Confirmar correo</label> <input
				type="email" class="form-control" name="confirmarCorreo"
				id="confirmarCorreo" maxlength="50"
				placeholder="Ej: ejemplo@ejemplo.es" <?php echo 'value="'.$datos["correoConf"].'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- Cotraseña-->
		<div class="form-group">
			<label for="contrasenia">Contraseña</label> <input type="password"
				class="form-control" name="contrasenia" id="contrasenia"
				pattern=".{8,50}" maxlength="50" placeholder="Ej: ***********"
				required />
			<div class="validation"></div>
		</div>
		<!-- Confirmar Contraseña -->
		<div class="form-group">
			<label for="confirmarContrasenia">Confirmar contraseña</label> <input
				type="password" class="form-control" name="confirmarContrasenia"
				id="confirmarContrasenia" maxlength="50"
				placeholder="Ej: ***********" required />
			<div class="validation"></div>
		</div>
		<!-- Direccion -->
		<div class="form-group">
			<label for="direccion">Dirección</label> <input type="text"
				class="form-control" name="direccion" id="direccion" maxlength="100"
				placeholder="Ej: C/ Picadero Nº23, Antequera, Málaga 29200" 
				<?php echo 'value="'.$datos["direccion"].'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- Telefono -->
		<div class="form-group">
			<label for="telefono">Teléfono</label> <input type="tel"
				class="form-control" name="telefono" id="telefono" maxlength="12"
				placeholder="Ej: +34634743432" <?php echo 'value="'.$datos["telefono"].'"';?> />
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="registrar">Registrar</button>
		</div>
	</form>
</div>
<?php
}
