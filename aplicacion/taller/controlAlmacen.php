<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$articulos = $ACL->dameArticulos();
$tiposArticulos = $ACL->dameTiposArticulos();

$datos = [
    "campo"=>"",
    "palabra"=>"",
    "borrados"=>0,
    "codTipoArticulo"=>0
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
            $articulos = $ACL->getArticulosFiltrados($datos["campo"], $datos["palabra"], $datos["borrados"]);
        }
        
    }
    
    if(isset($_POST["limpiar"])) {
        $datos["campo"] = "";
        $datos["palabra"] = "";
        $articulos = $ACL->dameArticulos();
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($articulos, $tiposArticulos, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
    <?php
}

function cuerpo($articulos, $tiposArticulos, $datos, $errores)
{
    ?>
    <section id="contact" class="section-bg wow fadeInUp">
    
    	<div class="container">
    
            <div class="section-header">
                <h2>Control de almacén</h2>
                <p>Gestiona todos los artículos</p>
            </div>
            
            <div class="text-center">
        		<a id="añadir" href="./nuevoArticulo.php">Añadir un nuevo articulo
        			<img id="plus" src="/imagenes/taller/plus.png">
    			</a>
    		</div><br>
		
		
		<?php 
		filtrado($datos, $errores);
		tablaArticulos($articulos, $tiposArticulos);
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
    				<option value="codTipoArticulo">Tipo de artículo</option>
    				<option value="codReferencia">Código de referencia</option>
    				<option value="nombre">Nombre</option>
    				<option value="proveedor">Proveedor</option>
    				<option value="descripcion">Descripción</option>
    			</select>
    			<div class="validation"></div>
    		</div>
        		
    		<!-- Palabra -->
    		<div class="form-group">
    			<label for="palabra">Palabra</label> 
    			<input type="text" class="form-control" name="palabra" id="palabra" maxlength="50" style="width: 400px;"
    				placeholder="Ej: Componente" value="<?php echo $datos["palabra"];?>" />
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

function tablaArticulos($articulos, $tiposArticulos) {
    ?>
    <div class="text-center">
        <table>
        	<tr>
        		<th></th>
        		<th>Borrado</th>
        		<th>Tipo de artículo</th>
        		<th>Código de referencia</th>
        		<th>Nombre</th>
        		<th>Proveedor</th>
        		<th>Descripción</th>
        		<th>Stock</th>
        		<th>Precio de compra (€)</th>
        		<th>Precio de venta (€)</th>
        	</tr>
        	
        	<?php
        	$cont = 0;
        	foreach($articulos as $articulo) {
        	    
        	    
            	if($cont%2==0) {
            	    echo '<tr';
            	    
            	    if($articulo["stock"] <= $articulo["stockMinimo"]) {
            	        echo ' style="background-color: #ff9999;"';
            	    }
            	    else {
            	        echo ' style="background-color: #fee7ea;"';
            	    }
            	    
            	    echo '>';
            	        
        	    }
        	    else {
        	        echo '<tr';
        	        if($articulo["stock"] <= $articulo["stockMinimo"]) {
        	            echo ' style="background-color: #ff9999;"';
        	        }
        	        echo '>';
        	    }
        	    
        	    
        	    $cont++;
        	    if($articulo["stock"]<=$articulo["stockMinimo"]) {
        	        echo '<td style="background-color: #ffffff;"><img src="/imagenes/aviso.png" title="¡Poco stock!" alt="Icono de aviso"></td>';
        	    }
        	    else {
        	        echo '<td style="background-color: #ffffff;"></td>';
        	    }
        	    echo '<td>';
        	    echo $articulo["borrado"]!=0?'Si':'No';
        	    echo '</td>' .
            	    '<td><a href="/aplicacion/taller/gestionArticulo.php?codArticulo='.$articulo["codArticulo"].'">'.$tiposArticulos[$articulo["codTipoArticulo"]].'</a></td>'.
            	    '<td><a href="/aplicacion/taller/gestionArticulo.php?codArticulo='.$articulo["codArticulo"].'">'.$articulo["codReferencia"].'</a></td>'.
            	    '<td><a href="/aplicacion/taller/gestionArticulo.php?codArticulo='.$articulo["codArticulo"].'">'.$articulo["nombre"].'</a></td>'.
            	    '<td>'.$articulo["proveedor"].'</td>'.
            	    '<td>'.$articulo["descripcion"].'</td>'.
            	    '<td>'.$articulo["stock"].'</td>'.
            	    '<td>'.$articulo["precioCompra"].'</td>'.
            	    '<td>'.$articulo["precioVenta"].'</td>'.
            	    '</tr>';
        	}
        	?>	
        </table>
    </div>
    <?php
}