<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "nombre"=>"",
    "apellidos"=>"",
    "dni_cif"=>"",
    "correo"=>"",
    "contrasenia"=>"",
    "direccion"=>"",
    "telefono"=>"",
    "codRol"=>0
];
$errores = [];

$roles = $ACL->dameRoles();

$actualizado = false;

if ($_POST) {
    
    if (isset($_POST["anadir"])) {
        
        // NOMBRE
        $nombre = "";
        if (isset($_POST["nombre"])) {
            $nombre = $_POST["nombre"];
        }
        
        $nombre = mb_strtoupper($nombre);
        
        if (mb_strlen($nombre) <= 2) {
            $errores["Nombre"] = "Su nombre debe contener más de 2 caracteres";
            $nombre = "";
        }
        
        $datos["nombre"] = $nombre;
        
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
        if (isset($_POST["dni_cif"])) {
            $dni = $_POST["dni_cif"];
        }
        
        $dni = mb_strtoupper($dni);
        
        if (! preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            $errores["dni_cif"] = "DNI o CIF no válido. Debe seguir este formato 00000000A";
            $dni = "";
        }
        
        $datos["dni_cif"] = $dni;
        
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
            $errores["confirmarCorreo"] = "Los correos no son iguales";
            $confCorreo = "";
        }
        
        // CONSTRASEÑA
        $contra = "";
        
        if (isset($_POST["contrasenia"])) {
            $contra = md5($_POST["contrasenia"]);
        }
        
        $datos["contrasenia"] = $contra;
        
        // CONFIRMAR CONTRASEÑA
        $confContra = "";
        if (isset($_POST["confirmarContrasenia"])) {
            $confContra = md5($_POST["confirmarContrasenia"]);
        }
        
        if ($contra != $confContra) {
            $errores["confirmarContrasenia"] = "Las contraseñas no coinciden";
            $confContra = "";
        }
        
        // DIRECCION
        $dir = "";
        if (isset($_POST["direccion"])) {
            $dir = $_POST["direccion"];
        }
        
        $dir = mb_strtoupper($dir);
        
        if ($dir == "" | mb_strlen($dir) < 10) {
            $errores["direccion"] = "Direccion no valida";
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
        
        if ((mb_strlen($tel) == 12 || mb_strlen($tel) == 11) && ! preg_match("/^\+[0-9]{10,11}$/", $tel)) {
            $errores["Teléfono"] = "Telefono no valido. Asegura no poner espacios";
            $tel = "";
        }
        
        $datos["telefono"] = $tel;
        
        //Rol
        $rol = "";
        if(isset($_POST["rol"])) {
            $rol = $_POST["rol"];
        }
        
        if($rol == "--Elige un rol--") {
            $errores["rol"] = "Debe elegir un tipo de bicicleta";
            $rol = "";
        }
        
        if (($codRol = $ACL->getCodRol($rol)) != false) {
           $datos["codRol"] = intval($codRol);
        }
        
        if (empty($errores)) {
            $ACL->anadirCliente($datos["dni_cif"], $datos["nombre"], $datos["apellidos"], $datos["correo"], $datos["contrasenia"], $datos["direccion"], $datos["telefono"], $datos["codRol"]);
            $actualizado = true;
        }
    }
    
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $roles, $actualizado);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $roles, $hecho)
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
				<h2>Añadir Cliente</h2>
				<p>Añadir un cliente nuevo</p>
			</div>
    			    <?php
	       formulario($datos, $errores, $roles);
        ?>
			</div>
        <?php
    } else {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Añadido!</h2>
				<p>Se ha añadido el cliente con éxito</p>
				<br>
				<p><a href="/aplicacion/taller/clientes.php">Volver a gestión de clientes</a><p>
			</div>
		</div>
		<?php
    }
    ?>
		
	</section>

</main>

<?php
}

function formulario($datos, $errores, $roles)
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
	<p>* Marca los campos obligatorios</p>
	<form action="" method="post" role="form" class="contactForm">
		<!-- Nombre -->
		<div class="form-group">
			<label for="nombre">Nombre*</label> <input type="text"
				class="form-control" name="nombre" id="nombre" maxlength="30"
				placeholder="Ej: Juan" value="<?php echo $datos['nombre'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Apellidos -->
		<div class="form-group">
			<label for="apellidos">Apellidos</label> <input type="text"
				class="form-control" name="apellidos" id="apellidos" maxlength="50"
				placeholder="Ej: Sanchez" 
				 value="<?php echo $datos['apellidos'];?>" />
			<div class="validation"></div>
		</div>
		<!-- DNI CIF -->
		<div class="form-group">
			<label for="dni_cif">DNI o CIF*</label> <input type="text"
				class="form-control" name="dni_cif" id="dni_cif" maxlength="9"
				placeholder="Ej: 00000000A" value="<?php echo $datos['dni_cif'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Correo -->
		<div class="form-group">
			<label for="correo">Correo electrónico*</label> <input type="text"
				class="form-control" name="correo" id="correo" maxlength="50"
				placeholder="Ej: patata@gmail.com" 
				 value="<?php echo $datos['correo'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Correo -->
		<div class="form-group">
			<label for="confirmarCorreo">Correo electrónico*</label> <input type="text"
				class="form-control" name="confirmarCorreo" id="confirmarCorreo" maxlength="50"
				placeholder="Ej: patata@gmail.com" required />
			<div class="validation"></div>
		</div>
		<!-- Contraseña -->
		<div class="form-group">
			<label for="contrasenia">Contraseña*</label> <input type="password"
				class="form-control" name="contrasenia" id="contrasenia"
				placeholder="Ej: **********" value="<?php echo $datos['contrasenia'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Confirmar contraseña -->
		<div class="form-group">
			<label for="confirmarContrasenia">Confirmar contraseña*</label> <input type="password"
				class="form-control" name="confirmarContrasenia" id="confirmarContrasenia"
				placeholder="Ej: **********" required />
			<div class="validation"></div>
		</div>
		<!-- Direccion -->
		<div class="form-group">
			<label for="direccion">Dirección*</label> <input type="text"
				class="form-control" name="direccion" id="direccion" maxlength="100"
				placeholder="Ej: C/ Inventada, Nº 23 Pueblo Provincia 20923" value="<?php echo $datos['direccion'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- telefono -->
		<div class="form-group">
			<label for="telefono">Teléfono*</label> <input type="text"
				class="form-control" name="telefono" id="telefono" maxlength="12"
				placeholder="Ej: +34639283943" 
				 value="<?php echo $datos['telefono'];?>" required />
			<div class="validation"></div>
		</div>
		
		<!-- Rol -->
		<div class="form-group">
			<label for="rol">Rol*</label><br>
			<select name="rol" id="rol" class="form-control">
				<option>--Elige un rol--</option>
				<?php 
				    foreach ($roles as $clave=>$valor) {
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

