<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codCliente = $_GET["codCliente"];
$cliente = $ACL->getCliente($codCliente);
$roles = $ACL->dameRoles();

$datos = [
    "nombre"=>"",
    "apellidos"=>"",
    "dni_cif"=>"",
    "correo"=>"",
    "direccion"=>"",
    "telefono"=>"",
    "codRol"=>0
];
$errores = [];

$actualizado = false;
$borrado = false;
$deshecho = false;

if($_POST) {
    
    if(isset($_POST["actualizar"])) {
        
        // NOMBRE
        $nombre = "";
        if (isset($_POST["nombre"])) {
            $nombre = mb_strtoupper($_POST["nombre"]);
        }
        
        if($nombre != $cliente["nombre"]) {
            if (mb_strlen($nombre) <= 2) {
                $errores["Nombre"] = "Su nombre debe contener más de 2 caracteres";
                $nombre = "";
            }
            
            $datos["nombre"] = $nombre;
            $ACL->actualizarCliente('nombre', $datos ["nombre"], $codCliente);
        }
        
        // APELLIDOS
        $ape = "";
        if (isset($_POST["apellidos"])) {
            $ape = mb_strtoupper($_POST["apellidos"]);
        }
        
        if($ape != $cliente["apellidos"]) {
        
            if (mb_strlen($ape) < 3) {
                $errores["Apellidos"] = "Sus apellidos debe contener más de 5 caracteres";
                $ape = "";
            }
            
            $datos["apellidos"] = $ape;
            $ACL->actualizarCliente('apellidos', $datos ['apellidos'], $codCliente);
        }
        
        // DNI
        $dni = "";
        if (isset($_POST["dni_cif"])) {
            $dni = mb_strtoupper($_POST["dni_cif"]);
        }
        
        if($dni != $cliente["dni_cif"]) {
        
            if (! preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
                $errores["dni_cif"] = "DNI o CIF no válido. Debe seguir este formato 00000000A";
                $dni = "";
            }
            
            $datos["dni_cif"] = $dni;
            $ACL->actualizarCliente('dni_cif', $dni, $codCliente);
        }
        
        // CORREO
        $correo = "";
        if (isset($_POST["correo"])) {
            $correo = mb_strtoupper($_POST["correo"]);
        }
        
        if($correo != $cliente["correo"]) {
            
            if (! preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $correo)) {
                $errores["Correo"] = "Correo no válido";
                $correo = "";
            }
            
            $datos["correo"] = $correo;
            $ACL->actualizarCliente('correo', $correo, $codCliente);
        }
        
        // DIRECCION
        $dir = "";
        if (isset($_POST["direccion"])) {
            $dir = mb_strtoupper($_POST["direccion"]);
        }
        
        if($dir != $cliente["direccion"]) {
        
            if ($dir == "" | mb_strlen($dir) < 10) {
                $errores["direccion"] = "Direccion no valida";
                $dir = "";
            }
            
            $datos["direccion"] = $dir;
            $ACL->actualizarCliente('direccion', $dir, $codCliente);
        }
        
        // TELEFONO
        $tel = "";
        if (isset($_POST["telefono"])) {
            $tel = $_POST["telefono"];
        }
        
        if($tel != $cliente["telefono"]) {
        
            if (mb_strlen($tel) == 9 && preg_match("/^[67][0-9]{8}$/", $tel)) {
                $tel = "+34" . $tel;
            }
            
            if ((mb_strlen($tel) == 12 || mb_strlen($tel) == 11) && ! preg_match("/^\+[0-9]{10,11}$/", $tel)) {
                $errores["Teléfono"] = "Telefono no valido. Asegura no poner espacios";
                $tel = "";
            }
            
            $datos["telefono"] = $tel;
            $ACL->actualizarCliente('telefono', $tel, $codCliente);
        }
        
        //Rol
        $rol = "";
        if (isset($_POST["tipoArt"])) {
            $rol = $_POST["tipoArt"];
        }
        
        if (($codRol = $ACL->getCodigoRol($rol)) != false) {
            
            if ($codRol != $cliente["codRol"]) {
                
                $datos['codRol'] = $codRol;
                $ACL->actualizarCliente('codRol', $codRol, $codCliente);
            }
        }
        
        if (empty($errores)) {
            $actualizado = true;
        }
        
    }
    
    if (isset($_POST["borrar"])) {
        if ($ACL->borrarCliente($codCliente))
            $borrado = true;
    }
    
    if (isset($_POST["deshacer"])) {
        if ($ACL->actualizarCliente('borrado', 0, $codCliente));
            $deshecho = true;
    }
    
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($actualizado, $borrado, $deshecho, $datos, $errores, $cliente, $roles);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <script src="/javascript/taller/gestionBici.js"></script>
	<?php
}

function cuerpo($actualizado, $borrado, $deshecho, $datos, $errores, $cliente, $roles)
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
				<p>Se ha borrado el cliente<br> 
					<strong><a id="volverAlmacen" href="/aplicacion/taller/clientes.php">
						Volver a clientes
					</a></strong>
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
				<p>Se ha deshecho el borrado del cliente<br> 
					<strong><a id="volverAlmacen" href="/aplicacion/taller/clientes.php">
						Volver a clientes
					</a></strong>
				<p>
			</div>
			
		</div>
        
       <?php  
    }
    else if (!empty($errores) || !$actualizado) {
        ?>
	    <div class="container">

			<div class="section-header">
				<h2>Modificar cliente</h2>
				<p>Modificar datos de un cliente</p>
			</div>			
	    <?php
        formulario($datos, $errores, $cliente, $roles);
        if($cliente["borrado"] == 0) {
            ?>
        	<hr>
			<div class="form">
				<form action="" method="post" role="form" class="contactForm">
					<div class="text-center">
						<h4>¿Deseas borrar este cliente?</h4>
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
						<h4>¿Deseas deshacer el borrado de este cliente?</h4>
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
					Se han actualizado los datos cel cliente con éxito<br> 
					<strong> <a id="volverAlmacen" href="/aplicacion/taller/clientes.php">
						Volver al taller
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

function formulario($datos, $errores, $cliente, $roles)
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
		<!-- Nombre -->
		<div class="form-group">
			<label for="nombre">Nombre</label> <input type="text"
				class="form-control" name="nombre" id="nombre" maxlength="30"
				placeholder="Ej: Juan Pablo"
				value="<?php echo $datos["nombre"]==""?$cliente['nombre']:$datos['nombre'];?>"
				required />
			<div class="validation"></div>
		</div>
		<!-- Apellidos -->
		<div class="form-group">
			<label for="apellidos">Apellidos</label> <input type="text"
				class="form-control" name="apellidos" id="apellidos" maxlength="50"
				placeholder="Ej: Sanchez Muñoz"
				value="<?php echo $cliente['apellidos'];?>" />
			<div class="validation"></div>
		</div>
		<!-- DNI o CIF -->
		<div class="form-group">
			<label for="dni_cif">DNI o CIF</label> <input type="text"
				class="form-control" name="dni_cif" id="dni_cif" maxlength="9"
				placeholder="Ej: 00000000A"
				value="<?php echo $cliente['dni_cif'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Correo -->
		<div class="form-group">
			<label for="correo">Correo</label> <input type="text"
				class="form-control" name="correo" id="correo" maxlength="50" 
				placeholder="Ej: patata@gmail.com"
				value="<?php echo $cliente['correo'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Direccion -->
		<div class="form-group">
			<label for="direccion">Dirección</label> <input type="text"
				class="form-control" name="direccion" id="direccion" maxlength="100" 
				placeholder="Ej: C/ Inventada, N 23 Pueblo Provincia 00000"
				value="<?php echo $cliente['direccion'];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Direccion -->
		<div class="form-group">
			<label for="telefono">Teléfono</label> <input type="text"
				class="form-control" name="telefono" id="telefono" maxlength="12" 
				placeholder="Ej: 674303923"
				value="<?php echo $cliente['telefono'];?>" required />
			<div class="validation"></div>
		</div>

		<!-- Tipo de articulo -->
		<div class="form-group">
			<label for="tipoArt">Tipo de artículo</label>
			<br> 
			<select name="tipoArt" id="tipoArt" class="form-control">
				<?php
                foreach ($roles as $clave => $valor) {
                    echo "<option name='{$clave}'";
                    if ($clave == $cliente["codRol"])
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
