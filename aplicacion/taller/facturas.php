<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$facturas = $ACL->dameFacturas();
$bicis = $ACL->dameBicis();
$nombreClientes = $ACL->dameNombreApellidosClientes();

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
            $errores["campo"] = "Debe elegir un campo";
            $campo = "";
        }
        
        $datos["campo"] = $campo;
        
        //Palabra
        $palabra = "";
        if(isset($_POST["palabra"])) {
            $palabra = $_POST["palabra"];
        }
        
        if($palabra == "") {
            $errores["palabra"] = "Debe escribir algo en palabra";
        }
        
        if($datos["campo"] == "fecha") {
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
            if($datos["campo"] == "fecha") {
                $facturas = $ACL->getFacturasFiltradas($datos["campo"], $datos["fecha"]);
            }
            else {
                $facturas = $ACL->getFacturasFiltradas($datos["campo"], $datos["palabra"]);
            }
        }
        
    }
    
    if(isset($_POST["limpiar"])) {
        $datos["campo"] = "";
        $datos["palabra"] = "";
        unset($_POST);
        $facturas = $ACL->dameFacturas();
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($facturas, $ACL, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($facturas, $ACL, $datos, $errores)
{
    ?>
    <section id="contact" class="section-bg wow fadeInUp">
    
    	<div class="container">
    
            <div class="section-header">
                <h2>Facturas</h2>
                <p>Gestiona las facturas</p>
            </div>
		
		<?php 
		filtrado($datos, $errores);
		tablaFactura($facturas, $ACL);
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
    			<select name="campo" id="campo" class="form-control">
    				<option>- Elige un campo -</option>
    				<option value="codCliente">Cliente</option>
    				<option value="codBici">Modelo de bicicleta</option>
    				<option value="fecha">Fecha</option>
    			</select>
    			<div class="validation"></div>
    		</div>
        		
    		<!-- Palabra -->
    		<div class="form-group">
    			<label for="palabra">Palabra</label> 
    			<input type="text" class="form-control" name="palabra" id="palabra" maxlength="50" style="width: 400px;"
    				placeholder="Ej: Juan" value="<?php echo $datos["palabra"];?>"/>
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

function tablaFactura($facturas, $ACL) {
    ?>
    <div class="text-center">
        <table align="center">
        	<tr>
        		<th>Cliente</th>
        		<th>Modelo de bicicleta</th>
        		<th>Fecha</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($facturas as $factura) {
        	    if($cont%2==0) {
        	        echo '<tr style="background-color: #fee7ea">';
        	    }
        	    else {
        	        echo '<tr>';
        	    }
        	    $cont++;
        	    $hojaTrabajo = $ACL->getHojaTrabajo($factura["codHojaTrabajo"]);
        	    $bici = $ACL->getBicicleta($hojaTrabajo["codBici"]);
        	    $cliente = $ACL->getCliente($bici["codCliente"]);
        	    echo '<td><a href="/aplicacion/taller/gestionFactura.php?codFactura='.$factura["codFactura"].'">'.$cliente["nombre"].' '.$cliente["apellidos"].'</a></td>'.
        	    '<td><a href="/aplicacion/taller/gestionFactura.php?codFactura='.$factura["codFactura"].'">'.$ACL->getBicicleta($hojaTrabajo["codBici"])["modelo"].'</a></td>'.
        	    '<td>'.Utilidades::fechaSqlANormalGuion($factura["fecha"]).'</td>'.
        	    '</tr>';
        	}
        	?>	
        </table>
    </div>
    <?php
}