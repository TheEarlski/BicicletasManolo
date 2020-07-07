<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$codHojaTrabajo = $_GET["codHojaTrabajo"];
$hojaTrabajo = $ACL->getHojaTrabajo($codHojaTrabajo);
$horasTrabajo = $ACL->dameHorasTrabajo();
$componentes = $ACL->dameComponentes();
$bici = $ACL->getBicicleta($hojaTrabajo["codBici"]);
$cliente = $ACL->getCliente($bici["codCliente"]);

$datos = [
    "estado"=>"",
    "fecha"=>""
];
$errores = [];

$hecho = false;

if ($_POST) {
    
    if(isset($_POST["actualizar"])) {
        
        //Reparado
        $reparado = 0;
        if(isset($_POST["reparado"])) {
            $reparado = intval($_POST["reparado"]);
        }
        
        if($reparado != 0) {
            if(!$ACL->existeFacturaDeHoja($codHojaTrabajo)) {
                $ACL->crearFactura($codHojaTrabajo);
                $factura = $ACL->getFacturaConHoja($codHojaTrabajo);
                $artsHoja = $ACL->dameArticulosDeHojaTrabajo($codHojaTrabajo);
                foreach($artsHoja as $articuloHoja) {
                    $articulo = $ACL->getArticulo($articuloHoja["codArticulo"]);
                    if(intval($articulo["codTipoArticulo"]) != 3) {
                        $ACL->anadirArticuloFactura($articuloHoja["unidades"], $articuloHoja["importe"]/1.21, $articuloHoja["importe"]-($articuloHoja["importe"]/1.21), 0, $articuloHoja["importe"]*$articuloHoja["unidades"],
                            $articuloHoja["codArticulo"], $factura["codFactura"], $articuloHoja["codArtHoja"]);
                    }
                    else {
                        $ACL->anadirArticuloFactura($articuloHoja["unidades"], $articuloHoja["importe"], 0, 0, $articuloHoja["importe"], 
                            $articuloHoja["codArticulo"], $factura["codFactura"], $articuloHoja["codArtHoja"]);
                    }
                }
                $mensaje = "Hola ".mb_convert_case($cliente["nombre"], MB_CASE_TITLE, "UTF-8").",".
                    "\n\nLe enviamos este correo para confirmar que su bicicleta ".mb_convert_case($bici["modelo"], MB_CASE_TITLE, "UTF-8")." ya está reparada y lista para ser recogida.".
                    "\n\n¡Gracias por confiar en Bicicletas Manolo!";
                Utilidades::enviarCorreo($cliente["correo"], $cliente["nombre"], 'Bicicleta reparada', $mensaje);
            }
        }
        
        $ACL->actualizarHojaTrabajo("reparada", $reparado, $codHojaTrabajo);
        
        //Recogido
        $recogido = 0;
        if(isset($_POST["recogido"])) {
            $recogido = intval($_POST["recogido"]);
        }
        
        $ACL->actualizarHojaTrabajo("recogida", $recogido, $codHojaTrabajo);
        
        if($recogido != 0) {
            $date = getdate();
            $hoy = $date["year"].'-'.$date["mon"].'-'.$date["mday"];
            $ACL->actualizarHojaTrabajo("fechaCierre", $hoy, $codHojaTrabajo);
            $ACL->actualizarHojaTrabajo("reparada", 1, $codHojaTrabajo);
            
            $mensaje = "Hola ".mb_convert_case($cliente["nombre"], MB_CASE_TITLE, "UTF-8").",".
                "\n\nLe enviamos este correo para confirmar que su bicicleta ".mb_convert_case($bici["modelo"], MB_CASE_TITLE, "UTF-8")." ha sido recogida.".
                "\n\n¡Gracias por confiar en Bicicletas Manolo!";
            Utilidades::enviarCorreo($cliente["correo"], $cliente["nombre"], 'Bicicleta recogida', $mensaje);
        }
        if($recogido == 0 && $hojaTrabajo["fechaCierre"] != "") {
            $ACL->actualizarHojaTrabajo("fechaCierre", NULL, $codHojaTrabajo);
        }
        
        //Fecha
        $fecha="";
        if(isset($_POST["fecha"])) {
            $fecha = $_POST["fecha"];
        }
        
        //Estado
        $estado = "";
        if(isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        }
        
        if($estado != "") {
            $ACL->anadirEstado($estado, $fecha, $codHojaTrabajo);
            $mensaje = "Hola ".mb_convert_case($cliente["nombre"], MB_CASE_TITLE, "UTF-8").",".
                "\n\nLe enviamos este correo para notificarle que se ha actualizado el estado de su bicicleta ".mb_convert_case($bici["modelo"], MB_CASE_TITLE, "UTF-8").".".
                "\nEl estado siguiente se actualizó el dia ".Utilidades::fechaSqlANormalGuion($fecha).": ".$estado." .".
                "\n\n¡Gracias por confiar en Bicicletas Manolo!";
            Utilidades::enviarCorreo($cliente["correo"], $cliente["nombre"], 'Bicicleta recogida', $mensaje);
        }
        
        //TiempoEmpleado
        $tiempoPrecio = "";
        if(isset($_POST["tiempoEmpleado"])) {
            $tiempoPrecio = $_POST["tiempoEmpleado"];
            $tiempo = explode(" (", $tiempoPrecio)[0];
        }
        
        if($tiempo !== "--Elige--") {
            $codArticulo = $ACL->getCodigoArticulo($tiempo);
            $articulo = $ACL->getArticulo($codArticulo);
            $codArtHoja = $ACL->anadirArticuloHojaTrabajo($articulo["codArticulo"], $codHojaTrabajo, 1, $articulo["precioVenta"]);
            
            if($ACL->existeFacturaDeHoja($codHojaTrabajo)) {
                $factura = $ACL->getFacturaConHoja($codHojaTrabajo);
                $ACL->anadirArticuloFactura(1, $articulo["precioVenta"], 0, 0, $articulo["precioVenta"], $codArticulo, $factura["codFactura"], $codArtHoja);
            }
        }
        
        //Componente
        $componentePrecio = "";
        if(isset($_POST["componente"])) {
            $componentePrecio = $_POST["componente"];
            $componente = explode(" (", $componentePrecio)[0];
        }
        
        //Unidades
        $unidades = 0;
        if(isset($_POST["unidades"])) {
            $unidades = intval($_POST["unidades"]);
        }
        
        if($componentes != "--Elige--") {
            $codArticulo = $ACL->getCodigoArticulo($componente);
            $articulo = $ACL->getArticulo($codArticulo);
            $codArtHoja = $ACL->anadirArticuloHojaTrabajo($articulo["codArticulo"], $codHojaTrabajo, $unidades, $articulo["precioVenta"]*$unidades);
            $ACL->actualizarArticulo("stock", $articulo["stock"]-$unidades, $articulo["codArticulo"]);
            
            if($ACL->existeFacturaDeHoja($codHojaTrabajo)) {
                $factura = $ACL->getFacturaConHoja($codHojaTrabajo);
                $ACL->anadirArticuloFactura($unidades, $articulo["precioVenta"], $articulo["precioVenta"]*0.21, 0, $articulo["precioVenta"]*1.21, $codArticulo, $factura["codFactura"], $codArtHoja);
            }
        }
        
        $hecho = true;
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $hecho, $hojaTrabajo, $componentes, $horasTrabajo);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>	
    <link href="/estilos/taller/style.css" rel="stylesheet">
    <link href="/estilos/taller/estados.css" rel="stylesheet">
    <script src="/javascript/taller/estados.js"></script>
    <?php
}

function cuerpo($datos, $errores, $hecho, $hojaTrabajo, $componentes, $horasTrabajo)
{
    ?>
<main id="main">

	<!--==========================
      Register Section
    ============================-->
	<section id="contact" class="section-bg wow fadeInUp">
	
		<div class="container">

		<?php
        if (! empty($errores) || ! $hecho) {
        ?>
			<div class="section-header">
				<h2>Añadir estado</h2>
				<p>Añadir un nuevo estado</p>
			</div>
			
			<div class="text-center">
				<img src="/imagenes/aviso.png" height="32px" title="Aviso">
				<p id="aviso"><strong>Se enviará un correo de notificación al cliente cuando se marca como REPARADA, RECOGIDA o cuando se añade un nuevo ESTADO.</strong></p>
			</div>	
	    <?php
        formularioActualizar($datos, $errores, $hojaTrabajo, $componentes, $horasTrabajo);
        }
        else {
        ?>
			<div class="section-header">
				<h2>¡Añadido!</h2>
				<p>Se ha añadido el estado con éxito<br>
					<strong> <a id="volverAlmacen" href="/aplicacion/taller/gestionHojaTrabajo.php?codHojaTrabajo=<?php echo $hojaTrabajo["codHojaTrabajo"];?>">
						Volver a la hoja de trabajo
					</a> </strong>
				<p>
			</div>
		<?php
        }
        ?>
    	</div>
	</section>
</main>

<?php
}

function formularioActualizar($datos, $errores, $hojaTrabajo, $componentes, $horasTrabajo)
{
    ?>
<div class="form">
	<form action="" method="post" role="form" class="contactForm" id="actualizarForm">
		<!-- Reparado -->
		<div class="form-group">
			Reparado
            <label class="switch">
           		<input type="hidden" name="reparado" value="0">	
				<input type="checkbox" name="reparado" id="switchReparado" value="1"
				<?php
				    if($hojaTrabajo["reparada"] != 0)
				        echo ' checked';
				?>
				>
				<span class="slider round"></span>
    		</label>
		</div>
		<!-- Recogido -->
		<div class="form-group">
    			Recogido
                <label class="switch">
                	<input type="hidden" name="recogido" value="0">
    				<input type="checkbox" name="recogido" id="switchRecogido" value="1"
    				<?php 
    				    if($hojaTrabajo["reparada"]==0)
    				        echo " disabled";
    				    if($hojaTrabajo["recogida"]!=0)
    				        echo " checked";
    				?>
    				>
    				<span class="slider round"></span>
        		</label>
    			<img id="imgInfo" src="/imagenes/taller/infoBlue.png" height="20px" title="Una bicicleta tiene que estar reparada para poder ser recogida">
		</div>
		<!-- Fecha -->
		<div class="form-group">
			<label for="fecha">Fecha</label> 
			<input type="date" class="form-control" name="fecha" id="fecha" maxlength="30" 
			<?php echo 'value="'.date('Y-m-d').'"';?> required />
			<div class="validation"></div>
		</div>
		<!-- Estado -->
		<div class="form-group">
			<label for="estado">Estado</label><br>
			<textarea form="actualizarForm" rows="5" cols="133" name="estado" placeholder="Ej: Problema solucionado"></textarea>
			<div class="validation"></div>
		</div>
		
		<div class="form-group">
			<label for="tiempoEmpleado">Tiempo empleado</label>
			<select name="tiempoEmpleado" id="tiempoEmpleado" class="form-control">
			<option>--Elige--</option>
				<?php
                foreach ($horasTrabajo as $horaTrabajo) {
                    echo "<option name='{$horaTrabajo["codArticulo"]}'>{$horaTrabajo["nombre"]} ({$horaTrabajo["precioVenta"]}€)</option>";
                }
                ?>
			</select>
			<div class="validation"></div>
		</div>
		
		<div class="form-group">
			<label for="componente">Componente usado</label>
			<select name="componente" id="componente" class="form-control">
			<option>--Elige--</option>
				<?php
				foreach ($componentes as $componente) {
				    echo "<option name='{$componente["codArticulo"]}'>{$componente["nombre"]} ({$componente["precioVenta"]}€)</option>";
				}
                ?>
			</select>
			<div class="validation"></div>
		</div>
		
		<div class="form-group">
			<label for="unidades">Unidades (de componentes)</label>
			<input type="number" name="unidades" value="1" min="1" class="form-control">
			<div class="validation"></div>
		</div>

		<div class="text-center">
			<button type="submit" name="actualizar">Actualizar</button>
		</div>
	</form>
</div>
 <?php
}