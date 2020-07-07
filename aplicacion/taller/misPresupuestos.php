 <?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$presupuestos = [];
$bicisCliente = [];

$Acceso = new Acceso();
if ($Acceso->hayUsuario()) {
    $codCliente = $ACL->getCodigoCliente($Acceso->getCorreo());
    $bicisCliente = $ACL->getBicisCliente($codCliente);
    foreach($bicisCliente as $bici) {
        $presupuestos = $ACL->getPresupuestosCliente($bici["codBici"]);
    }
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($Acceso, $presupuestos, $bicisCliente, $ACL);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
	<link href="/estilos/taller/style.css" rel="stylesheet">
	<?php
}

function cuerpo($Acceso, $presupuestos, $bicisCliente, $ACL)
{
    ?>
<section id="contact" class="section-bg wow fadeInUp">

	<div class="container">

		<div class="section-header">
			<h2>Mis presupuestos</h2>
			<p>Mira sus presupuestos</p>
		</div>
    <?php
    if (!$Acceso->hayUsuario()) {
        ?>
         	<div class="form">
			<div class="text-center">
				<h4>Es necesario iniciar sesión</h4>
				<button type="submit" class="button" onclick="window.location.href='/aplicacion/inicio/login.php';">
				Inicia sesión</button>
			</div>
		</div>
        <?php
    } else {
        ?>
         	<div class="text-center">
			<h4>
				<strong>Mis presupuestos</strong>
			</h4>
        <?php
        if (!empty($presupuestos)) {
                tabla($presupuestos, $bicisCliente, $ACL);
            } 
            else {
        ?>
				<h3>No tiene ningun presupuesto en nuestra tienda</h3>
       	<?php
            }
    }
    ?>	</div>
	</div>
</section>
<?php
}

function tabla($presupuestos, $bicisCliente, $ACL)
{
    ?>
<table align="center">
	<tr>
		<th>Bicicleta</th>
		<th>Descripción</th>
		<th>Precio estimado</th>
	</tr>
	<?php 
	$cont = 0;
	foreach($presupuestos as $presupuesto) {
	    if($cont%2==0) {
	        echo '<tr style="background-color: #fee7ea;">';
	    }
	    else {
	        echo '<tr>';
	    }
	    echo '<td>'.$bicisCliente[$cont]["modelo"].'</td>' .
    	    '<td>'.$presupuesto["descripcion"].'</td>' .
    	    '<td>';
	    echo sprintf('%0.2f', $ACL->getImportePresupuesto($presupuesto["codPresupuesto"])["precioPresupuesto"]);
    	echo ' €</td>' .
    	    '</tr>';
	    $cont++;
	}
	?>
</table>
<?php
}