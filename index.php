<?php
include_once (dirname(__FILE__) . "./cabecera.php");

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo();
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
<!-- Main Stylesheet File -->
<link href="/estilos/style.css" rel="stylesheet">
<?php
}

function cuerpo()
{
    ?>
<!--==========================
    Intro Section
  ============================-->
<section id="intro">
	<div class="intro-container wow fadeIn">
		<h1 class="mb-4 pb-0">
			Bicicletas<br> <span>Manolo</span>
		</h1>
		<p class="mb-4 pb-0">Bienvenido al mundo de Manolo</p>
		<a href="https://www.youtube.com/watch?v=5EE8m8mmq1k&hl=es&cc_lang_pref=es&cc_load_policy=1"
			class="venobox play-btn mb-4" data-vbtype="video"
			data-autoplay="true"></a> <a href="./aplicacion/taller/taller.php"
			class="about-btn scrollto">Ir al taller</a>
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
						Manolo.</p>
				</div>
				<div class="col-lg-3">
					<h3>Dónde</h3>
					<p>
						<a href="https://goo.gl/maps/7gjuu8EZStUwMrmJ7">C/ Picadero, Nº 23<br>Antequera<br>Málaga<br>29200
						</a>
					</p>
				</div>
				<div class="col-lg-3">
					<h3>Horario</h3>
					<p>
						Lunes a Viernes<br>De 9:00 a 14:00 y de 16:00 a 19:00
					</p>
					<p>
						Sábado<br>De 9:30 a 13:00
					</p>
				</div>
			</div>
		</div>
	</section>

	<!--==========================
      Speakers Section
    ============================-->
	<section id="speakers" class="wow fadeInUp">
		<div class="container">
			<div class="section-header">
				<h2>Empleados actuales</h2>
				<p>Aquí tienes a nuestro equipo de empleados actualmente</p>
			</div>

			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="speaker">
						<img src="imagenes/empleados/1.jpg" alt="Speaker 1"
							class="img-fluid">
						<div class="details">
							<h3>Manuel Galeote</h3>
							<p>Propietario</p>
							<div class="social">
								<a href=""><i class="fa fa-twitter"></i></a> <a href=""><i
									class="fa fa-facebook"></i></a> <a href=""><i
									class="fa fa-linkedin"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="speaker">
						<img src="imagenes/empleados/2.jpg" alt="Speaker 2"
							class="img-fluid">
						<div class="details">
							<h3>Terry Hart</h3>
							<p>Administrativo</p>
							<div class="social">
								<a href=""><i class="fa fa-twitter"></i></a> <a href=""><i
									class="fa fa-facebook"></i></a> <a href=""><i
									class="fa fa-linkedin"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="speaker">
						<img src="imagenes/empleados/3.jpg" alt="Speaker 3"
							class="img-fluid">
						<div class="details">
							<h3>Carlos Javier Bigote</h3>
							<p>Reparador</p>
							<div class="social">
								<a href=""><i class="fa fa-twitter"></i></a> <a href=""><i
									class="fa fa-facebook"></i></a> <a href=""><i
									class="fa fa-linkedin"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="speaker">
						<img src="imagenes/empleados/4.jpg" alt="Speaker 4"
							class="img-fluid">
						<div class="details">
							<h3>Davina Sito</h3>
							<p>Dependienta y Reparadora</p>
							<div class="social">
								<a href=""><i class="fa fa-twitter"></i></a> <a href=""><i
									class="fa fa-facebook"></i></a> <a href=""><i
									class="fa fa-linkedin"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="speaker">
						<img src="imagenes/empleados/5.jpg" alt="Speaker 5"
							class="img-fluid">
						<div class="details">
							<h3>Samuel Magatos</h3>
							<p>Dependiente y Reparador</p>
							<div class="social">
								<a href=""><i class="fa fa-twitter"></i></a> <a href=""><i
									class="fa fa-facebook"></i></a> <a href=""><i
									class="fa fa-linkedin"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="speaker">
						<img src="imagenes/empleados/6.jpg" alt="Speaker 6"
							class="img-fluid">
						<div class="details">
							<h3>Roberto Gusano</h3>
							<p>Reparador</p>
							<div class="social">
								<a href=""><i class="fa fa-twitter"></i></a> <a href=""><i
									class="fa fa-facebook"></i></a> <a href=""><i
									class="fa fa-linkedin"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>

	<!--==========================
      Venue Section
    ============================-->
	<section id="venue" class="wow fadeInUp">

		<div class="container-fluid">

			<div class="section-header">
				<h2>Localización</h2>
				<p>Información sobre la localidad y galería</p>
			</div>

			<div class="row no-gutters">
				<div class="col-lg-6 venue-map">
					<iframe
						src="https://maps.google.com/maps?width=100%&height=600&hl=es&coord=37.0199381, -4.5645729&q=C%2F%20Picadero%20Antequera+(Bicicletas%20Manolo)&ie=UTF8&t=&z=17&iwloc=B&output=embed"
						style="border: 0"></iframe>
				</div>

				<div class="col-lg-6 venue-info">
					<div class="row justify-content-center">
						<div class="col-11 col-lg-8">
							<h3>
								Tienda de <br>Bicicletas Manolo Antequera
							</h3>
							<p>Nuestro centro está localizado en el centro de Antequera con
								accesibilidad fácil para todos y sus bicicletas.</p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="container-fluid venue-gallery-container">
			<div class="row no-gutters">

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/1.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/1.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/2.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/2.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/3.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/3.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/4.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/4.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/5.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/5.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/6.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/6.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/7.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/7.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-md-4">
					<div class="venue-gallery">
						<a href="imagenes/venue-gallery/8.jpg" class="venobox"
							data-gall="venue-gallery"> <img
							src="imagenes/venue-gallery/8.jpg" alt="" class="img-fluid">
						</a>
					</div>
				</div>

			</div>
		</div>

	</section>

	<!--==========================
      Gallery Section
    ============================-->
	<section id="gallery" class="wow fadeInUp">

		<div class="container">
			<div class="section-header">
				<h2>Galería</h2>
				<p>Una galería de nuestros eventos recientes</p>
			</div>
		</div>

		<div class="owl-carousel gallery-carousel">
			<a href="imagenes/gallery/1.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/1.jpg"
				alt=""></a> <a href="imagenes/gallery/2.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/2.jpg"
				alt=""></a> <a href="imagenes/gallery/3.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/3.jpg"
				alt=""></a> <a href="imagenes/gallery/4.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/4.jpg"
				alt=""></a> <a href="imagenes/gallery/5.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/5.jpg"
				alt=""></a> <a href="imagenes/gallery/6.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/6.jpg"
				alt=""></a> <a href="imagenes/gallery/7.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/7.jpg"
				alt=""></a> <a href="imagenes/gallery/8.jpg" class="venobox"
				data-gall="gallery-carousel"><img src="imagenes/gallery/8.jpg"
				alt=""></a>
		</div>

	</section>

	<!--==========================
      F.A.Q Section
    ============================-->
	<section id="faq" class="wow fadeInUp">

		<div class="container">

			<div class="section-header">
				<h2>Preguntas frecuentes (FAQ)</h2>
			</div>

			<div class="row justify-content-center">
				<div class="col-lg-9">
					<ul id="faq-list">

						<li><a data-toggle="collapse" class="collapsed" href="#faq1">Non
								consectetur a erat nam at lectus urna duis? <i
								class="fa fa-minus-circle"></i>
						</a>
							<div id="faq1" class="collapse" data-parent="#faq-list">
								<p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id
									volutpat lacus laoreet non curabitur gravida. Venenatis lectus
									magna fringilla urna porttitor rhoncus dolor purus non.</p>
							</div></li>

						<li><a data-toggle="collapse" href="#faq2" class="collapsed">Feugiat
								scelerisque varius morbi enim nunc faucibus a pellentesque? <i
								class="fa fa-minus-circle"></i>
						</a>
							<div id="faq2" class="collapse" data-parent="#faq-list">
								<p>Dolor sit amet consectetur adipiscing elit pellentesque
									habitant morbi. Id interdum velit laoreet id donec ultrices.
									Fringilla phasellus faucibus scelerisque eleifend donec
									pretium. Est pellentesque elit ullamcorper dignissim. Mauris
									ultrices eros in cursus turpis massa tincidunt dui.</p>
							</div></li>

						<li><a data-toggle="collapse" href="#faq3" class="collapsed">Dolor
								sit amet consectetur adipiscing elit pellentesque habitant
								morbi? <i class="fa fa-minus-circle"></i>
						</a>
							<div id="faq3" class="collapse" data-parent="#faq-list">
								<p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices
									sagittis orci. Faucibus pulvinar elementum integer enim. Sem
									nulla pharetra diam sit amet nisl suscipit. Rutrum tellus
									pellentesque eu tincidunt. Lectus urna duis convallis convallis
									tellus. Urna molestie at elementum eu facilisis sed odio morbi
									quis</p>
							</div></li>

						<li><a data-toggle="collapse" href="#faq4" class="collapsed">Ac
								odio tempor orci dapibus. Aliquam eleifend mi in nulla? <i
								class="fa fa-minus-circle"></i>
						</a>
							<div id="faq4" class="collapse" data-parent="#faq-list">
								<p>Dolor sit amet consectetur adipiscing elit pellentesque
									habitant morbi. Id interdum velit laoreet id donec ultrices.
									Fringilla phasellus faucibus scelerisque eleifend donec
									pretium. Est pellentesque elit ullamcorper dignissim. Mauris
									ultrices eros in cursus turpis massa tincidunt dui.</p>
							</div></li>

						<li><a data-toggle="collapse" href="#faq5" class="collapsed">Tempus
								quam pellentesque nec nam aliquam sem et tortor consequat? <i
								class="fa fa-minus-circle"></i>
						</a>
							<div id="faq5" class="collapse" data-parent="#faq-list">
								<p>Molestie a iaculis at erat pellentesque adipiscing commodo.
									Dignissim suspendisse in est ante in. Nunc vel risus commodo
									viverra maecenas accumsan. Sit amet nisl suscipit adipiscing
									bibendum est. Purus gravida quis blandit turpis cursus in</p>
							</div></li>

						<li><a data-toggle="collapse" href="#faq6" class="collapsed">Tortor
								vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc
								eget lorem dolor? <i class="fa fa-minus-circle"></i>
						</a>
							<div id="faq6" class="collapse" data-parent="#faq-list">
								<p>Laoreet sit amet cursus sit amet dictum sit amet justo.
									Mauris vitae ultricies leo integer malesuada nunc vel.
									Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget
									lorem dolor sed. Ut venenatis tellus in metus vulputate eu
									scelerisque. Pellentesque diam volutpat commodo sed egestas
									egestas fringilla phasellus faucibus. Nibh tellus molestie nunc
									non blandit massa enim nec.</p>
							</div></li>

					</ul>
				</div>
			</div>

		</div>

	</section>

	<!--==========================
      Subscribe Section
    ============================-->
	<section id="subscribe">
		<div class="container wow fadeInUp">
			<div class="section-header">
				<h2>Hoja informativa</h2>
				<p>Suscribe para recibir las ultimas noticias de todo relacionado
					con Bicicletas Manolo</p>
			</div>

			<form method="POST" action="#">
				<div class="form-row justify-content-center">
					<div class="col-auto">
						<input type="text" class="form-control"
							placeholder="Introduce su correo">
					</div>
					<div class="col-auto">
						<button type="submit">Suscribir</button>
					</div>
				</div>
			</form>

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
							<a href="https://goo.gl/maps/7gjuu8EZStUwMrmJ7"> C/ Picadero Nº
								23, Antequera, Málaga, 29200 </a>
						</address>
					</div>
				</div>

				<div class="col-md-4">
					<div class="contact-phone">
						<i class="ion-ios-telephone-outline"></i>
						<h3>Teléfono</h3>
						<p>
							<a href="tel:+ +34952534398"> +34 952 534 398</a>
						</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="contact-email">
						<i class="ion-ios-email-outline"></i>
						<h3>Correo electrónico</h3>
						<p>
							<a href="mailto:bicicletasmanolo2019@gmail.com">proyecyoBicicletasManolo@gmail.com</a>
						</p>
					</div>
				</div>

			</div>

		</div>
	</section>
	<!-- #contact -->

</main>

<?php
}
