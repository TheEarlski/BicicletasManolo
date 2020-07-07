<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$bicis = $ACL->dameBicis();

$tiposBicis = $ACL->dameTiposBicis();
$clientes = $ACL->dameNombreApellidosClientes();

$datos = [
    "campo"=>"",
    "palabra"=>"",
    "borrados"=>0,
    "codTipoBici"=>0,
    "codCliente"=>[]
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
            $bicis = $ACL->getBicisFiltrados($datos["campo"], $datos["palabra"], $datos["borrados"]);
        }
    }
    
    if(isset($_POST["limpiar"])) {
        $datos["campo"] = "";
        $datos["palabra"] = "";
        $datos["borrados"] = 0;
        $bicis = $ACL->dameBicis();
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($bicis, $tiposBicis, $clientes, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
<link href="/estilos/taller/style.css" rel="stylesheet">
<?php
}

function cuerpo($bicis, $tiposBicis, $clientes, $datos, $errores)
{
    ?>
<section id="contact" class="section-bg wow fadeInUp">

	<div class="container">

		<div class="section-header">
			<h2>Gestión de bicicletas</h2>
			<p>Gestiona todas las bicicletas actuales y reparadas</p>
		</div>
		
		<div class="text-center">
			<a id="añadir" href="/aplicacion/taller/nuevaBicicleta.php">Añadir una bicicleta
				<img id="plus" src="/imagenes/taller/plus.png">
			</a>
		</div>
		
		<?php
		filtrado($datos, $errores);
        tablaBicis($bicis, $tiposBicis, $clientes);
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
<br>
    <form action="" method="post" role="form">
    	<div id="filtro">
        
            <!-- Campo -->
        	<div class="form-group">
    			<label for="campo">Campo</label><br>
    			<select name="campo" id="campo" class="form-control" >
    				<option>- Elige un campo -</option>
    				<option value="codCliente">Cliente</option>
    				<option value="modelo">Modelo</option>
    				<option value="codTipoBici">Tipo de bicicleta</option>
    				<option value="descripcion">Descripción</option>
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

function tablaBicis($bicis, $tiposBicis, $clientes)
{
    ?>
<div class="text-center">
	
	<table>
		<tr>
			<th>Borrado</th>
			<th>Cliente</th>
			<th>Modelo</th>
			<th>Tipo de bicicleta</th>
			<th>Descripción</th>
			<th>Marchas</th>
			<th>Tamaño ruedas</th>
			<th>Peso</th>
		</tr>
        	
    	<?php
    $cont = 0;
    foreach ($bicis as $bici) {
        if ($cont % 2 == 0) {
            echo '<tr style="background-color: #fee7ea">';
        } else {
            echo '<tr>';
        }
        $cont ++;
        echo '<td>';
        echo $bici["borrado"]!=0?'Si':'No';
        echo '</td>' .
            '<td><a href="/aplicacion/taller/gestionBicicleta.php?codBici=' . $bici["codBici"] . '">' . $clientes[$bici["codCliente"]] . '</a></td>' .
            '<td><a href="/aplicacion/taller/gestionBicicleta.php?codBici=' . $bici["codBici"] . '">' . $bici["modelo"] . '</a></td>' . 
            '<td><a href="/aplicacion/taller/gestionBicicleta.php?codBici=' . $bici["codBici"] . '">' . $tiposBicis[$bici["codTipoBici"]] . '</a></td>' .
            '<td>' . $bici["descripcion"] . '</td>' . 
            '<td>' . $bici["marchas"] . '</td>' . 
            '<td>' . $bici["tamanioRuedas"] . ' pulgadas</td>' . 
            '<td>' . $bici["peso"] . ' kg</td>' . 
            '</tr>';
    }
    ?>	
        </table>
</div>
<?php
}