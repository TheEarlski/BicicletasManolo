<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$hojas = $ACL->dameHojasTrabajo();
$nombreClientes = $ACL->dameNombreApellidosClientes();
$bicis = $ACL->dameBicis();

$datos = [
    "campo"=>"",
    "palabra"=>"",
    "codCliente"=>0,
    "codBici"=>0,
    "fecha"=>""
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
        
        if($datos["campo"] == "fechaApertura" || $datos["campo"] == "fechaCierre") {
            $fecha = $_POST["palabra"];
            if(preg_match('/^[0-9]{1,2}[\/-][0-9]{1,2}[\/-][0-9]{4}$/', $fecha) || preg_match('/^[0-9]{1,2}[\/-][0-9]{1,4}$/', $fecha)) {
                $datos["fecha"] = Utilidades::fechaNormalASQL($palabra);
            }
            else if(preg_match('/^[0-9]{1,4}$/', $fecha)) {
                $datos["fecha"] = $palabra;
            }
            else {
                $errores["Fecha"] = "La fecha no es valida. Debe seguir el formato DD-MM-AAAA";
            }
            
        }
        
        $datos["palabra"] = $palabra;
        
        if(empty($errores)) {
            if($datos["campo"] == "fechaApertura" || $datos["campo"] == "fechaCierre") {
                $hojas = $ACL->getHojasTrabajoFiltrados($datos["campo"], $datos["fecha"]);
            }
            else {
                $hojas = $ACL->getHojasTrabajoFiltrados($datos["campo"], $datos["palabra"]);
            }
        }
        
    }
    
    if(isset($_POST["limpiar"])) {
        $datos["campo"] = "";
        $datos["palabra"] = "";
        $hojas = $ACL->dameHojasTrabajo();
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($hojas, $ACL, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($hojas, $ACL, $datos, $errores)
{
    ?>
    <section id="contact" class="section-bg wow fadeInUp">
    
    	<div class="container">
    
            <div class="section-header">
                <h2>Hojas de trabajo</h2>
                <p>Gestiona las hojas de trabajo</p>
            </div>
            
            <div class="text-center">
            	<a id="añadir" href="./nuevaHojaTrabajo.php">Añadir una hoja de trabajo / entrega
    				<img id="plus" src="/imagenes/taller/plus.png">
    			</a>
            </div>
		
		<?php 
		filtrado($datos, $errores);
		tablaHojas($hojas, $ACL);
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
    			<select name="campo" id="campo" class="form-control" >
    				<option>- Elige un campo -</option>
    				<option value="codCliente">Cliente</option>
    				<option value="codBici">Modelo de bicicleta</option>
    				<option value="problema">Problema</option>
    				<option value="fechaApertura">Fecha de apertura</option>
    				<option value="fechaCierre">Fecha de cierre</option>
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

function tablaHojas($hojas, $ACL) {
    ?>
    <div class="text-center">
        <table align="center">
        	<tr>
        		<th>Reparada</th>
        		<th>Recogida</th>
        		<th>Cliente</th>
        		<th>Modelo de bicicleta</th>
        		<th>Problema</th>
        		<th>Fecha de apertura</th>
        		<th>Fecha de cierre</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($hojas as $hoja) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #fee7ea">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    $bici = $ACL->getBicicleta($hoja["codBici"]);
        	    $nombreApellido = $ACL->getNombreApellidoCliente($bici["codCliente"]);
        	    echo '<td>';
        	    echo $hoja["reparada"]!=0?('<img src="/imagenes/taller/greenTick.png">'):('<img src="/imagenes/taller/redCross.png">');
            	echo '</td><td>';
            	echo $hoja["recogida"]!=0?('<img src="/imagenes/taller/greenTick.png">'):('<img src="/imagenes/taller/redCross.png">');
            	echo '</td>'.
        	    '<td><a href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo='.$hoja["codHojaTrabajo"].'">'.$nombreApellido["nombre"].' '.$nombreApellido["apellidos"].'</a></td>'.
        	    '<td><a href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo='.$hoja["codHojaTrabajo"].'">'.$bici["modelo"].'</a></td>'.
        	    '<td><a href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo='.$hoja["codHojaTrabajo"].'">'.$hoja["problema"].'</a></td>'.
        	    '<td>'.Utilidades::fechaSqlANormalGuion($hoja["fechaApertura"]).'</td>'.
        	    '<td>';
        	    echo $hoja["fechaCierre"]==""? '-':Utilidades::fechaSqlANormalGuion($hoja["fechaCierre"]);
        	    echo '</td>'.
        	       '</tr>';
        	}
        	?>	
        </table>
    </div>
    <?php
}