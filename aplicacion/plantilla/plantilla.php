<?php

function pedirLogin()
{
    $_SESSION["paginaprev"] = $_SERVER["REQUEST_URI"];
    header("location: /aplicacion/inicio/login.php");
}

function paginaError($mensaje)
{
    header("HTTP/1.0 404 $mensaje");
    inicioCabecera("Bicicletas Manolo");
    finCabecera();
    inicioCuerpo("ERROR");
    echo "<br />\n";
    echo $mensaje;
    echo "<br />\n";
    echo "<br />\n";
    echo "<br />\n";
    echo "<a href='/index.php'>Ir a la pagina principal</a>\n";
    finCuerpo();
}

function inicioCabecera($titulo)
{
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?php echo $titulo ?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="Bradley">
<meta name="viewport" content="width=device-width; initialscale=1.0">

<!-- Favicons -->
<link href="/imagenes/favicon.png" rel="icon">
<link href="/imagenes/apple-touch-icon.png" rel="apple-touch-icon">

<!-- Bootstrap CSS File -->
<link href="/scripts/lib/bootstrap/css/bootstrap.min.css"
	rel="stylesheet">

<!-- Google Fonts -->
<link
	href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800"
	rel="stylesheet">

<!-- Libraries CSS Files -->
<link href="/scripts/lib/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="/scripts/lib/animate/animate.min.css" rel="stylesheet">
<link href="/scripts/lib/venobox/venobox.css" rel="stylesheet">
<link href="/scripts/lib/owlcarousel/assets/owl.carousel.min.css"
	rel="stylesheet">

<!-- JQuery -->
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<?php
}

function finCabecera()
{
    ?>
</head>
	<?php
}

function inicioCuerpo($cabecera)
{
    ?>
<body>
	<?php
    /*
     * if (empty($_COOKIE)) {
     *
     * $cadena = '<div id="documento" style="background-color:White ; color:Black">';
     * } else {
     * $colorFondo = isset($_COOKIE['colorFondo']) ? $_COOKIE['colorFondo'] : 'white';
     * $colorTexto = isset($_COOKIE['colorTexto']) ? $_COOKIE['colorTexto'] : 'black';
     *
     * $cadena = "\t<div id=\"documento\" style=\"background-color: {$colorFondo}; color:{$colorTexto}\">";
     * }
     * echo $cadena;
     */
    // <div id="documento">
    ?>
	<header id="header">
		<div class="container">

			<div id="logo" class="pull-left">
				<a href="/index.php" class="scrollto"><img src="/imagenes/logo.png"
					alt="Logo Bicicletas Manolo" title="Intro"></a>
			</div>

			<nav id="nav-menu-container">
				<ul class="nav-menu">
					<li><a href="/index.php">Inicio</a></li>
					<li class="menu-active"><a href="#intro">Principio</a></li>
					
				<?php 
				if(preg_match('/\/index\.php.?/', $_SERVER["REQUEST_URI"])) {
				?>
					<li><a href="#about">Info</a></li>
					<li><a href="#speakers">Empleados</a></li>
					<li><a href="#venue">Localización</a></li>
					<li><a href="#gallery">Galería</a></li>
					<li><a href="#contact">Contacto</a></li>
				<?php 
				}
				else if(preg_match('/\/aplicacion\/taller\/taller\.php.?/', $_SERVER["REQUEST_URI"])) {
			    ?>
			    	<li><a href="#about">Info</a></li>
			    	<li><a href="#contact">Contacto</a></li>
					<li class="buy-tickets">
						<a href="/aplicacion/taller/misBicis.php">Mis bicicletas</a>
    				</li>
			    <?php
                }
                else {
			    ?>
			    	<li class="buy-tickets">
						<a href="/aplicacion/taller/misBicis.php">Mis bicicletas</a>
    				</li>
				<?php
                }
                ?>
                
                <li class="buy-tickets">
                	<a href="/aplicacion/taller/taller.php">Ir al taller</a>
                </li>
                
                <?php
				$Acceso = new Acceso();
				if($Acceso->gestionarBicicletas()) {
				?>
    				<li style="margin-top: -6px;">
    					<a href="/aplicacion/inicio/mensajes.php" class="scrollto">
                    		<img src="/imagenes/mensaje.png" alt="Icono mensajes" title="Mensajes">
                		</a>
    				</li>
				<?php
				}
				?>
    				
				</ul>
				
			</nav>
			<!-- #nav-menu-container -->
		</div>

		<div id="login">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php 
			if($_SESSION["acceso"]["validado"]) {
			    ?>
			    <a href="/aplicacion/inicio/logout.php">
			    	<img class="iconoSesion" src="/imagenes/loginIcon.png" height="35px" alt="Profile" title="Profile icon"> Cerrar sesión
				</a>
			    <?php
			}
			else {
			    ?>
			    <a href="/aplicacion/inicio/login.php">
			     	<img class="iconoSesion" src="/imagenes/loginIcon.png" height="35px" alt="Profile" title="Profile icon"> Iniciar sesión
			    </a> 
			    <?php
			}
			?>
            
				
		</div>
		
	</header>
	<!-- #header -->

	<div id="cuerpo">
	<?php
}

function finCuerpo()
{
    ?>
	</div>
	<!--==========================
    Footer
  ============================-->
	<footer id="footer">
		<div class="footer-top">
			<div class="container">
				<div class="row">

					<div class="col-lg-3 col-md-6 footer-info">
						<img src="/imagenes/logo.png" alt="Bicicletas Manolo">
						<p>Somos una tienda de bicicletas y un taller nuevo localizados en
							el centro de Antequera. Nos apasiona todo relacionado con el
							ciclcismo, desde el cuidado de las bicis hasta el cuidado de su
							salud. Para todo relacionado con el ciclismo, esta Bicicletas
							Manolo.</p>
					</div>

					<div class="col-lg-3 col-md-6 footer-links">
						<h4>Enlaces útiles</h4>
						<ul>
							<li><i class="fa fa-angle-right"></i> <a href="/index.php">Intro</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="/aplicacion/taller/taller.php">Taller</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="/aplicacion/taller/misBicis.php">Mis Bicicletas</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="/index.php#contact">Contacto</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="https://policies.google.com/privacy?hl=es">Política de privacidad</a></li>
						</ul>
					</div>

					<div class="col-lg-3 col-md-6 footer-links">
						<h4>Mapa</h4>
						<ul>
							<li><i class="fa fa-angle-right"></i> <a href="#">Inicio</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="#info">Info</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="#">Servicios</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="#">Términos de
									servicios</a></li>
							<li><i class="fa fa-angle-right"></i> <a href="https://policies.google.com/privacy?hl=es">Política de privacidad</a></li>
						</ul>
					</div>

					<div class="col-lg-3 col-md-6 footer-contact">
						<h4>Contáctanos</h4>
						<p>
							<strong>Dirección:</strong> <a
								href="https://goo.gl/maps/7gjuu8EZStUwMrmJ7"> C/ Picadero, Nº 23
								<br> Antequera Málaga 29200<br>
							</a> <strong>Teléfono:</strong><a href="tel:+ +34952534398"> +34
								952 534 398</a><br> <strong>Correo electrónico:</strong> <a
								href="mailto:bicicletasmanolo2019@gmail.com">proyectoBicicletasManolo@gmail.com</a>
						</p>

						<div class="social-links">
							<a href="https://twitter.com/bicisManolo" class="twitter"><i
								class="fa fa-twitter"></i></a> <a
								href="https://www.facebook.com/Bicicletas-Manolo-106206630847918"
								class="facebook"><i class="fa fa-facebook"></i></a> <a href="#"
								class="instagram"><i class="fa fa-instagram"></i></a> <a
								href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
							<a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
						</div>

					</div>

				</div>
			</div>
		</div>

		<div class="container">
			<div class="copyright">
				&copy; Copyright <strong>Bicicletas Manolo 2019-<script>document.write(new Date().getFullYear());</script></strong>.
				Todos los derechos reservados
			</div>
			<div class="credits">Diseñado por Bradley Earley</div>
		</div>
	</footer>
	<!-- #footer -->

	<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

	<!-- JavaScript Libraries -->
	<script src="/scripts/lib/jquery/jquery.min.js"></script>
	<script src="/scripts/lib/jquery/jquery-migrate.min.js"></script>
	<script src="/scripts/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/scripts/lib/easing/easing.min.js"></script>
	<script src="/scripts/lib/superfish/hoverIntent.js"></script>
	<script src="/scripts/lib/superfish/superfish.min.js"></script>
	<script src="/scripts/lib/wow/wow.min.js"></script>
	<script src="/scripts/lib/venobox/venobox.min.js"></script>
	<script src="/scripts/lib/owlcarousel/owl.carousel.min.js"></script>

	<!-- Template Main Javascript File -->
	<script src="/javascript/main.js"></script>
</body>
</html>
<?php
}