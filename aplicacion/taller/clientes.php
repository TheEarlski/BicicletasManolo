<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$clientes = $ACL->dameClientes();

$datos = [
    "campo"=>"",
    "palabra"=>"",
    "borrados"=>0
];
$errores = [];

if($_POST) {
    
    if(isset($_POST["filtrar"])) {
        
        //Campo
        $campo = "";
        if(isset($_POST["campo"])) {
            $campo = $_POST["campo"];
        }
        
        $datos["campo"] = $campo;
        
        //Palabra
        $palabra = "";
        if(isset($_POST["palabra"])) {
            $palabra = $_POST["palabra"];
        }
        
        if($datos["campo"] == "dni_cif") {
            $palabra = mb_strtoupper($palabra);
        }
        
        $datos["palabra"] = $palabra;
        
        //Borrados
        $borrados = 0;
        if(isset($_POST["borrados"])) {
            $borrados = intval($_POST["borrados"]);
        }
        
        $datos["borrados"] = $borrados;
        
        if($borrados == 0 && (($campo == "- Elige un campo -") || ($palabra == ""))) {
            $errores["borrado"] = "Tienes que elegir algun filtro";
        }
        
        if(empty($errores)) {
            $clientes = $ACL->getClientesFiltrados($datos["campo"], $datos["palabra"], $datos["borrados"]);
        }
        
    }
    
    if(isset($_POST["limpiar"])) {
        $datos["campo"] = "";
        $datos["palabra"] = "";
        $clientes = $ACL->dameClientes();
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($clientes, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
<link href="/estilos/taller/style.css" rel="stylesheet">
<?php
}

function cuerpo($clientes, $datos, $errores)
{
    ?>
<section id="contact" class="section-bg wow fadeInUp">

	<div class="container">

		<div class="section-header">
			<h2>Gestión de clientes</h2>
			<p>Gestiona todas los clientes actuales</p>
		</div>
		
		<div class="text-center">
    		<a id="añadir" href="./nuevoCliente.php">Añadir un cliente
    			<img id="plus" src="/imagenes/taller/plus.png">
			</a>
		</div><br>
		
		<?php
		filtrado($datos, $errores);
        tablaClientes($clientes);
        ?>
		
		</div>
</section>
<?php
}

function filtrado($datos, $errores) {
    if ($errores) { // mostrar los errores
        echo "<br><div class='error' style='color: red'>";
        foreach ($errores as $clave => $valor) {
            echo "$valor<br>" . PHP_EOL;
        }
        echo "</div><br>";
    }
    ?>
<div class="form">
    <form action="" method="post" role="form">
    	<div id="filtro">
        
            <!-- Campo -->
        	<div class="form-group">
    			<label for="campo">Campo</label><br>
    			<select name="campo" id="campo" class="form-control" >
    				<option>- Elige un campo -</option>
    				<option value="nombre">Nombre</option>
    				<option value="apellidos">Apellidos</option>
    				<option value="correo">Correo</option>
    				<option value="dni_cif">DNI o CIF</option>
    				<option value="telefono">Teléfono</option>
    			</select>
    			<div class="validation"></div>
    		</div>
        		
    		<!-- Palabra -->
    		<div class="form-group">
    			<label for="palabra">Palabra</label> 
    			<input type="text" class="form-control" name="palabra" id="palabra" maxlength="50" style="width: 400px;"
    				placeholder="Ej: Juan" value="<?php echo $datos["palabra"];?>" />
    			<div class="validation"></div>
    		</div>
    		
    		<!-- Borrados -->
    		<div class="form-group">
    			Borrados<br>
                <label class="switch">
                	<input type="hidden" name="borrados" value="0">
    				<input type="checkbox" name="borrados" id="switchBorrados" value="1"
    				<?php 
				    if($datos["borrados"] != 0)
				        echo "checked";
 				    ?>>
    				<span class="slider round"></span>
        		</label>
    		</div>
    		
    		<div class="text-center">
    		<br>
    			<button type="submit" id="btnFiltrar" name="filtrar">Filtrar</button>
    		</div>
    		
    		<div class="text-center">
    		<br>
    			<button type="submit" id="btnLimpiar" name="limpiar">Limpiar</button>
    		</div>
		</div>
    </form>
</div>
    
    <?php
}

function tablaClientes($clientes)
{
    ?>
<div class="text-center">
	<table>
		<tr>
			<th>Borrado</th>
			<th>Nombre</th>
			<th>Apellidos</th>
			<th>DNI / CIF</th>
			<th>Correo</th>
			<th>Dirección</th>
			<th>Teléfono</th>
		</tr>
        	
    	<?php
    $cont = 0;
    foreach ($clientes as $cliente) {
        if ($cont % 2 == 0) {
            echo '<tr style="background-color: #fee7ea">';
        } else {
            echo '<tr>';
        }
        $cont ++;
        echo '<td>';
        echo $cliente["borrado"]!=0?'Si':'No';
        echo '</td>' .
            '<td><a href="/aplicacion/taller/gestionCliente.php?codCliente=' . $cliente["codCliente"] . '">' . $cliente["nombre"] . '</a></td>' .
            '<td><a href="/aplicacion/taller/gestionCliente.php?codCliente=' . $cliente["codCliente"] . '">' . $cliente["apellidos"] . '</a></td>' .
            '<td><a href="/aplicacion/taller/gestionCliente.php?codCliente=' . $cliente["codCliente"] . '">' . $cliente["dni_cif"] . '</a></td>' .
            '<td><a href="/aplicacion/taller/gestionCliente.php?codCliente=' . $cliente["codCliente"] . '">' . $cliente["correo"] . '</a></td>' .
            '<td>' . $cliente["direccion"] . '</td>' .
            '<td>' . $cliente["telefono"] . '</td>' .
            '</tr>';
    }
    ?>	
        </table>
</div>
<?php
}