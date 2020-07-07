<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "nombre"=>"",
    "correo"=>"",
    "tema"=>"",
    "mensaje"=>""
];
$errores = [];

$enviado = false;

if($_POST) {
    if(isset($_POST["enviar"])) {
        
        //Nombre
        $nom = "";
        if(isset($_POST["nombre"])) {
            $nom = $_POST["nombre"];
        }
        
        if($nom == "") {
            $errores["nombre"] = "Debe introducir su nombre";
        }
        
        $datos["nombre"] = $nom;
        
        //Correo
        $correo = "";
        if(isset($_POST["correo"])) {
            $correo = mb_strtoupper($_POST["correo"]);
        }
        
        if($correo == "") {
            $errores["correo"] = "Debe introducir su correo";
        }
        
        $datos["correo"] = $correo;
        
        //Tema
        $tema = "";
        if(isset($_POST["tema"])) {
            $tema = $_POST["tema"];
        }
        
        if($tema == "") {
            $errores["tema"] = "Debe introducir un tema";
        }
        
        $datos["tema"] = $tema;
        
        //Mensaje
        $mensaje = "";
        if(isset($_POST["mensaje"])) {
            $mensaje = $_POST["mensaje"];
        }
        
        if($mensaje == "") {
            $errores["mensaje"] = "Debe introducir mensaje";
        }
        
        $datos["mensaje"] = $mensaje;
        
        if(empty($errores)) {
            $cliente = false;
            if($ACL->existeCliente($datos["correo"])) {
               $cliente = true; 
            }
            $ACL->anadirMensaje($datos["nombre"], $datos["correo"], $datos["tema"], $datos["mensaje"], $cliente);
            $mensajeCorreo = "Hola {$datos["nombre"]}, \n\nGracias por enviar este mensaje:\n'{$datos["mensaje"]}'.\nResponderemos a su mensaje lo antes posible. \n\nUn saludo,\nBicicletas Manolo";
            Utilidades::enviarCorreo($datos["correo"], $datos["nombre"], 'Mensaje recibido - '.$datos["tema"], $mensajeCorreo);
            $enviado = true;
        }
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($datos, $errores, $enviado);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
    <link href="/estilos/taller/estiloTaller.css" rel="stylesheet">
    <?php
}

function cuerpo($datos, $errores, $enviado)
{
    ?>
	 <section id="intro">
		<div class="intro-container wow fadeIn">
        <h1 class="mb-4 pb-0"><span>Taller</span><br>Bicicletas<br><span>Manolo</span></h1>
        <p class="mb-4 pb-0">Bienvenido al taller de Manolo</p>
        <?php
            echo '<a href="/aplicacion/taller/misBicis.php" class="about-btn scrollto">Mis bicicletas</a>';
            echo '<a href="/aplicacion/taller/misPresupuestos.php" class="about-btn scrollto">Mis presupuestos</a><br>';
            $Acceso = new Acceso();
			if($Acceso->gestionarArticulos()) {
			    echo '<a href="/aplicacion/taller/controlAlmacen.php" class="about-btn scrollto">Control de almacén</a>';
			}
			if($Acceso->gestionarClientes()) {
			    echo '<a href="/aplicacion/taller/clientes.php" class="about-btn scrollto">Gestionar clientes</a>';
			}
			if($Acceso->gestionarBicicletas()) {
			    echo '<a href="/aplicacion/taller/bicicletas.php" class="about-btn scrollto">Gestionar bicicletas</a>';
			    echo '<a href="/aplicacion/taller/presupuestos.php" class="about-btn scrollto">Presupuestos</a>';
			    echo '<a href="/aplicacion/taller/hojasTrabajo.php" class="about-btn scrollto">Hojas de trabajo / Entregas</a>';
			    echo '<a href="/aplicacion/taller/facturas.php" class="about-btn scrollto">Facturas</a>';
			}
			
		?>
		</div>
  	</section>

  <main id="main">
      
      

    <!--==========================
      About Section
    ============================-->
    <section id="about">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h2>Información</h2>
            <p>Somos una tienda de bicicletas y un taller nuevo localizados en
				el centro de Antequera. Nos apasiona todo relacionado con el
				ciclcismo, desde el cuidado de las bicis hasta el cuidado de su
				salud. Para todo relacionado con el ciclismo, esta Bicicletas
				Manolo.
			</p>
          </div>
          <div class="col-lg-3">
            <h3>Dónde</h3>
            <p><a href="https://goo.gl/maps/7gjuu8EZStUwMrmJ7">C/ Picadero, Nº 23<br>Antequera<br>Málaga<br>29200</a></p>
          </div>
          <div class="col-lg-3">
            <h3>Horario</h3>
            <p>Lunes a Viernes<br>De 9:00 a 14:00 y de 16:00 a 19:00</p>
            <p>Sábado<br>De 9:30 a 13:00</p>
          </div>
        </div>
      </div>
    </section>

    <!--==========================
      Contact Section
    ============================-->
    <section id="contact" class="section-bg wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2>Contáctanos</h2>
          <p>Póngase en contacto con nosotros</p>
        </div>

        <div class="row contact-info">

          <div class="col-md-4">
            <div class="contact-address">
              <i class="ion-ios-location-outline"></i>
              <h3>Dirección</h3>
              <address>
                <a href="https://goo.gl/maps/7gjuu8EZStUwMrmJ7">
                  C/ Picadero Nº 23, Antequera, Málaga, 29200
                </a>
              </address>
            </div>
          </div>

          <div class="col-md-4">
            <div class="contact-phone">
              <i class="ion-ios-telephone-outline"></i>
              <h3>Teléfono</h3>
              <p><a href="tel:+ +34952534398"> +34 952 534 398</a></p>
            </div>
          </div>

          <div class="col-md-4">
            <div class="contact-email">
              <i class="ion-ios-email-outline"></i>
              <h3>Correo electrónico</h3>
              <p><a href="mailto:bicicletasmanolo2019@gmail.com">proyectoBicicletasManolo@gmail.com</a></p>
            </div>
          </div>

        </div>

			<div class="form">
			
			<?php 
			if(!$enviado) {
				formularioMensaje($datos, $errores);
			}
			else {
		    ?>
		    	<div class="text-center">
    		    	<h2>¡Enviado!</h2>
    	        	<p>Se ha enviado su mensaje con éxito<p>
	        	</div>
		    <?php
			}
			?>
			</div>

		</div>
    </section>

  </main>
<?php
}

function formularioMensaje($datos, $errores) {
    if ($errores) { // mostrar los errores
        echo "<div class='error' style='color: red'>Errores<br>";
        foreach ($errores as $clave => $error) {
            echo "$error<br>" . PHP_EOL;
        }
        echo "</div><br>" . PHP_EOL;
    }
    ?>
    <form action="" method="post" role="form" class="contactForm">
    
		<div class="form-row">
			<div class="form-group col-md-6">
				<input type="text" name="nombre" class="form-control" id="nombre" placeholder="Su nombre" maxlength="50" />
				<div class="validation"></div>
			</div>
			<div class="form-group col-md-6">
				<input type="email" class="form-control" name="correo" id="correo" placeholder="Su correo" maxlength="50" />
				<div class="validation"></div>
			</div>
		</div>
		
		<div class="form-group">
			<input type="text" class="form-control" name="tema" id="tema" placeholder="El tema" maxlength="50" />
			<div class="validation"></div>
		</div>
		
		<div class="form-group">
			<textarea class="form-control" name="mensaje" rows="5" data-rule="required" placeholder="Su mensaje" maxlength="500"></textarea>
			<div class="validation"></div>
		</div>
		
		<div class="text-center">
			<button type="submit" name="enviar">Enviar mensaje</button>
		</div>
		
	</form>
    <?php
}
