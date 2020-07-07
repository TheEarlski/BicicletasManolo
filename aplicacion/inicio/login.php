<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "correo" => "",
    "contrasenia" => ""
];

$errores = [];

if (isset($_POST["validar"])) {
    $correo = "";
    if (isset($_POST["correo"])) {
        $correo = $_POST["correo"];
        $correo = mb_strtoupper(trim($correo));
        $correo = mb_substr($correo, 0, 50);
    }

    if ($correo == "") {
        $errores["Correo"][] = "Debe indicarse un correo";
    }
    
    $datos["correo"] = $correo;

    $contra = "";
    if (isset($_POST["contrasenia"])) {
        $contra = $_POST["contrasenia"];
    }

    if ($contra == '') {
        $errores["Contraseña"][] = "La contraseña no puede estar vacía";
    }
    
    $contra = md5($contra);

    if (empty($errores)) {
        if ($ACL->esValido($correo, $contra)) {
            $nombre = $ACL->getNombreCliente($correo);
            $gestBicis = false;
            $gestClien = false;
            $gestArti = false;
            if (! $ACL->getPermisos($correo, $gestBicis, $gestClien, $gestArti)) {
                paginaError("Error en la validacion");
                exit();
            }

            if ($Acceso->registrarUsuario($correo, $nombre, $gestBicis, $gestClien, $gestArti)) {
                // cargo la pagina
                if (isset($_SESSION["paginaprev"]))
                    header("location: " . $_SESSION["paginaprev"]);
                else
                    header("location: /index.php");
                exit();
            } else {
                $errores["correo"][] = "Correo o contraseña incorrectos";
            }
        } else {
            $errores["correo"][] = "Correo o contraseña no valido";
        }
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
<link href="/estilos/loginStyle.css" rel="stylesheet">
<?php
}

function cuerpo($datos, $errores)
{
    ?>
<main id="main">

	<!--==========================
      Login Section
    ============================-->
	<section id="contact" class="section-bg wow fadeInUp">

		<div class="container">

			<div class="section-header">
				<h2>Iniciar sesión</h2>
				<p>Entrar en su cuenta</p>
			</div>
			
			<?php 
			 formulario($datos, $errores);
			?>
		
			<hr>
			<div class="form">
    			<div class="text-center">
    				<h4>¿No tienes una cuenta?</h4>
    				<button type="submit" class="button" onclick="window.location.href='./register.php';">Regístrate</button>
    			</div>
			</div>

		</div>
	</section>
	<!-- #contact -->

</main>

<?php
}

function formulario($datos, $errores)
{
    if ($errores) { // mostrar los errores
        echo "<div class='error' style='color: red'>";
        foreach ($errores as $clave => $valor) {
            foreach ($valor as $error)
                echo "$error<br>" . PHP_EOL;
        }
        echo "</div><br>";
    }
    ?>

<div class="form">
	<form action="" method="post" role="form" class="contactForm">
		<div class="form-group">
			<label for="correo">Correo</label>
			<input type="text" class="form-control" name="correo" id="correo" placeholder="Ej: ejemplo@ejemplo.es" value="<?php echo $datos['correo'] ?>"/>
		</div>
		<div class="form-group">
			<label for="contrasenia">Contraseña</label>
			<input type="password" class="form-control" name="contrasenia" id="contrasenia" placeholder="Ej: **********" />
		</div>
		<div class="text-center">
			<button type="submit" class="button" name="validar">Iniciar sesión</button>
		</div>
	</form>
</div>


<?php
}