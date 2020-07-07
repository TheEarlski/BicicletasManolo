<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$presupuestos = $ACL->damePresupuestos();
$nombreClientes = $ACL->dameNombreApellidosClientes();
$bicis = $ACL->dameBicis();

$datos = [
    "campo"=>"",
    "palabra"=>"",
    "codCliente"=>0,
    "codBici"=>0
];
$errores = [];

if($_POST) {
    
    if(isset($_POST["filtrar"])) {
        
        //Campo
        $campo = "";
        if(isset($_POST["campo"])) {
            $campo = $_POST["campo"];
        }
        
        if($campo == "- Elige un campo -") {
            $errores["Campo"] = "Debe elegir un campo";
            $campo = "";
        }
        
        $datos["campo"] = $campo;
        
        //Palabra
        $palabra = "";
        if(isset($_POST["palabra"])) {
            $palabra = $_POST["palabra"];
        }
        
        if($palabra == "") {
            $errores["Palabra"] = "Debe escribir algo en el campo palabra";
        }
        
        $datos["palabra"] = $palabra;
        
        if(empty($errores)) {
            $presupuestos = $ACL->getPresupuestosFiltrados($datos["campo"], $datos["palabra"]);
        }
        
    }
    
    if(isset($_POST["limpiar"])) {
        $datos["campo"] = "";
        $datos["palabra"] = "";
        $presupuestos = $ACL->damePresupuestos();
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($presupuestos, $ACL, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($presupuestos, $ACL, $datos, $errores)
{
    ?>
    <section id="contact" class="section-bg wow fadeInUp">
    
    	<div class="container">
    
            <div class="section-header">
                <h2>Presupuestos</h2>
                <p>Gestiona los presupuestos</p>
            </div>
            
            <div class="text-center">
            	<a id="añadir" href="./nuevoPresupuesto.php">Añadir un presupuesto nuevo
    				<img id="plus" src="/imagenes/taller/plus.png">
    			</a>
            </div>
		
		<?php 
		filtrado($datos, $errores);
		tablaPresupuestos($presupuestos, $ACL);
		?>
		
		</div>
	</section>
	<?php
}

function filtrado($datos, $errores) {
    if ($errores) { // mostrar los errores
        echo "<br><div class='error' style='color: red'>";
        foreach ($errores as $clave => $valor) {
            echo "$clave: $valor<br>" . PHP_EOL;
        }
        echo "</div><br>";
    }
    ?>
<div class="form">
    <form action="" method="post" role="form">
    	<div id="filtro" style="margin-left: 50px; width: 90%;">
            <!-- Campo -->
        	<div class="form-group">
    			<label for="campo">Campo</label><br>
    			<select name="campo" id="campo" class="form-control" required >
    				<option>- Elige un campo -</option>
    				<option value="codCliente">Cliente</option>
    				<option value="codBici">Modelo de bicicleta</option>
    				<option value="descripcion">Descripción</option>
    			</select>
    			<div class="validation"></div>
    		</div>
        		
    		<!-- Palabra -->
    		<div class="form-group">
    			<label for="palabra">Palabra</label> 
    			<input type="text" class="form-control" name="palabra" id="palabra" maxlength="50" style="width: 400px;"
    				placeholder="Ej: Juan" value="<?php echo $datos["palabra"];?>" required  />
    			<div class="validation"></div>
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

function tablaPresupuestos($presupuestos, $ACL) {
    ?>
    <div class="text-center">
        <table align="center">
        	<tr>
        		<th>Cliente</th>
        		<th>Modelo de bicicleta</th>
        		<th>Descripción</th>
        		<th>Precio de presupuesto</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($presupuestos as $presupuesto) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #fee7ea">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    $bici = $ACL->getBicicleta($presupuesto["codBici"]);
        	    $nombreApellido = $ACL->getNombreApellidoCliente($bici["codCliente"]);
        	    echo '<td><a href="/aplicacion/taller/gestionPresupuesto.php?codPresupuesto='.$presupuesto["codPresupuesto"].'">'.$nombreApellido["nombre"].' '.$nombreApellido["apellidos"].'</a></td>'.
            	    '<td><a href="/aplicacion/taller/gestionPresupuesto.php?codPresupuesto='.$presupuesto["codPresupuesto"].'">'.$bici["modelo"].'</a></td>'.
            	    '<td><a href="/aplicacion/taller/gestionPresupuesto.php?codPresupuesto='.$presupuesto["codPresupuesto"].'">'.$presupuesto["descripcion"].'</a></td>'.
            	    '<td>'.sprintf('%0.2f', $ACL->getImportePresupuesto($presupuesto["codPresupuesto"])["precioPresupuesto"]).' €</td>'.
            	    '</tr>';
        	}
        	?>	
        </table>
    </div>
    <?php
}