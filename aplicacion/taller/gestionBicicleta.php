<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codBici = $_GET["codBici"];

$bici = $ACL->getBicicleta($codBici);
$cliente = $ACL->getNombreApellidoCliente($bici["codCliente"]);
$tiposBicis = $ACL->dameTiposBicis();

$datos = [
    "modelo"=>"",
    "descripcion"=>"",
    "tamanioRuedas"=>0,
    "marchas"=>0,
    "peso"=>0,
    "material"=>"",
    "codTipoBici"=>0
];
$errores = [];

$actualizado = false;
$borrado = false;
$deshecho = false;

if($_POST) {
    /*
     * MODIFICAR
     */
    if(isset($_POST["modificar"])) {
        
        $modelo = "";
        if(isset($_POST["modelo"])) {
            $modelo = mb_strtoupper($_POST["modelo"]);
        }
        
        if($modelo != $bici["modelo"]) {
        
            if($modelo == "") {
                $errores = "El modelo no puede estar vacío";
            }
            
            $datos["modelo"] = $modelo;
            $ACL->actualizarBicicleta("modelo", $modelo, $codBici);
        }
        
        //Descripcion
        $descrip = "";
        if(isset($_POST["descripcion"])) {
            $descrip = $_POST["descripcion"];
        }
        
        if($descrip != $bici["descripcion"]) {
            if($descrip == "") {
                $errores = "La descripción no puede estar vacía";
            }
            
            $datos["descripcion"] = $descrip;
            $ACL->actualizarBicicleta("descripcion", $descrip, $codBici);
        }
        
        //Tamaño ruedas
        $tamRue = "";
        if(isset($_POST["tamañoRuedas"])) {
            $tamRue = floatval($_POST["tamañoRuedas"]);
        }
        
        if($tamRue != $bici["tamanioRuedas"]) {
        
            $datos["tamanioRuedas"] = $tamRue;
            $ACL->actualizarBicicleta("tamanioRueda", $tamRue, $codBici);
        }
        
        //Marchas
        $marchas = "";
        if(isset($_POST["marchas"])) {
            $marchas = intval($_POST["marchas"]);
        }
        
        if($marchas != $bici["marchas"]) {
        
            $datos["marchas"] = $marchas;
            $ACL->actualizarBicicleta("marchas", $marchas, $codBici);
            
        }
        
        //Peso
        $peso = 0.0;
        if(isset($_POST["peso"])) {
            $peso = floatval($_POST["peso"]);
        }
        
        if($peso != $bici["peso"]) {
        
            $datos["peso"] = $peso;
            $ACL->actualizarBicicleta("peso", $peso, $codBici);
            
        }
        //Tipo bicicleta
        $tipoBici = "";
        if(isset($_POST["tipoBici"])) {
            $tipoBici = $_POST["tipoBici"];
        }
        
        if (($codTipoBici = $ACL->getCodigoTipoBicicleta($tipoBici)) != false) {
            
            if ($codTipoBici != $bici["codTipoBici"]) {
                
                $datos['codTipoBici'] = $codTipoBici;
                $ACL->actualizarBicicleta("codTipoBici", $codTipoBici, $codBici);
            }
        }
               
        if (empty($errores)) {
            $actualizado = true;
        }
    }
    
    /*
     * BORRAR
     */
    if(isset($_POST["borrar"])) {
        $ACL->borrarBicicleta($codBici);
        $borrado = true;
    }
    
    if(isset($_POST["deshacer"])) {
        $ACL->actualizarBicicleta("borrado", 0, $codBici);
        $deshecho = true;
    }
    
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $bici, $cliente, $tiposBicis, $actualizado, $borrado, $deshecho);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
	<?php
}

function cuerpo($datos, $errores, $bici, $cliente, $tiposBicis, $modificado, $borrado, $deshecho)
{
    ?>
    <section id="contact" class="section-bg wow fadeInUp">
		<?php 
		if ($borrado) {
        ?>
        <div class="container">

			<div class="section-header">
				<h2>¡Borrado!</h2>
				<p>Se ha borrado la bicicleta<br> 
					<strong><a id="volverAlmacen" href="/aplicacion/taller/bicicletas.php">
						Volver a bicicletas
					</a></strong>
				<p>
			</div>
			
		</div>
		<?php
        }
        else if ($deshecho) {
        ?>
        <div class="container">
			<div class="section-header">
				<h2>¡Borrado deshecho!</h2>
				<p>Se ha deshecho el borrado de la bicicleta<br> 
					<strong> 
						<a id="volverAlmacen" href="/aplicacion/taller/bicicletas.php">
							Volver a bicicletas
						</a>
					</strong>
				<p>
			</div>
		</div>
		<?php
        } 
        else if (!empty($errores) || !$modificado) {
        ?>
	    <div class="container">

			<div class="section-header">
				<h2>Modificar bicicleta</h2>
				<p>Modificar datos de una bicicleta</p>
			</div>			
	    <?php
        formulario($datos, $errores, $bici, $tiposBicis);
        
        if($bici["borrado"] == 0) {
            ?>
        	<hr>
			<div class="form">
				<form action="" method="post" role="form" class="contactForm">
					<div class="text-center">
						<h4>¿Deseas borrar esta bicicleta?</h4>
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
						<h4>¿Deseas deshacer el borrado de esta bicicleta?</h4>
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
					Se han actualizado los datos de la bicicleta con éxito<br> 
					<strong> <a id="volverAlmacen" href="/aplicacion/taller/bicicletas.php">
						Volver al taller
					</a> </strong>
				<p>
			</div>


		</div>
		<?php
        }
		?>
		</div>
	</section>
    <?php
}

function tablaEstado($estados) {
    ?>
	<table id="tablaEstado" >	
		<tr>
			<th colspan="7" style="font-size: 22px">Estados de la bicicleta</th>
		</tr>
		<tr>
			<th>Fecha</th>
			<th>Estado</th>
		</tr>
        	
	<?php
    foreach ($estados as $estado) {
        echo '<tr id="estado'.$estado["codEstado"].'">'.
            '<td>'.$estado["fecha"].'</td>'.
            '<td>'.$estado["estado"].'</td>'.
            '</tr>';
    }
    ?>
    </table>
<?php
}

function formulario($datos, $errores, $bici, $tiposBicis)
{
    ?>
<div class="form">
	<form action="" method="post" role="form" class="contactForm">
		<!-- Tipo de bicis -->
		<div class="form-group">
			<label for="tipoBici">Tipo de bicicleta</label>
			<br> 
			<select name="tipoBici" id="tipoBici" class="form-control">
				<?php
                foreach ($tiposBicis as $clave => $valor) {
                    echo "<option name='{$clave}'";
                    if ($clave == $bici["codTipoBici"])
                        echo " selected='selected'";
                    echo ">{$valor}</option>";
                }
                ?>
			</select>
			<div class="validation"></div>
		</div>
		<!-- Modelo -->
		<div class="form-group">
			<label for="modelo">Modelo</label>
			<input type="text" class="form-control" name="modelo" id="modelo" 
					value="<?php echo $bici["modelo"];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Descripcion -->
		<div class="form-group">
			<label for="descripcion">Descripción</label>
			<input type="text" class="form-control" name="descripcion" id="descripcion" 
					value="<?php echo $bici["descripcion"];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Tamaño ruedas -->
		<div class="form-group">
			<label for="tamañoRuedas">Tamaño de ruedas</label>
			<input type="text" class="form-control" name="tamañoRuedas" id="tamañoRuedas" 
					value="<?php echo $bici["tamanioRuedas"];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Marchas -->
		<div class="form-group">
			<label for="marchas">Marchas</label>
			<input type="text" class="form-control" name="marchas" id="marchas" 
					value="<?php echo $bici["marchas"];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Peso -->
		<div class="form-group">
			<label for="peso">Peso</label>
			<input type="text" class="form-control" name="peso" id="peso" 
					value="<?php echo $bici["peso"];?>" required />
			<div class="validation"></div>
		</div>
		<!-- Material -->
		<div class="form-group">
			<label for="material">Material</label>
			<input type="text" class="form-control" name="material" id="material" 
					value="<?php echo $bici["material"];?>" required />
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="modificar">Modificar</button>
		</div>
	</form>
</div>
<?php
}