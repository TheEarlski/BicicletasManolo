<?php
include_once (dirname(__FILE__) . "/../../cabecera.php");

$mensajesSinLeer = $ACL->dameMensajesSinLeer();
$mensajesLeidos = $ACL->dameMensajesLeidos();

if($_POST) {
    if(isset($_POST["leidos"])) {
        
        foreach($mensajesSinLeer as $mensaje) {
            $codigos[] = $mensaje["codMensaje"];
        }
        $max = intval(max($codigos));
        
        $codMensajes = [];
        for($i=1; $i<=$max; $i++) {
            if(isset($_POST["mensaje".$i])) {
                $codMensajes[] = $i;
            }
        }
        
        if(!empty($codMensajes)) {
            $ACL->marcarMensajesComoLeidos($codMensajes);
        }
    }
    
    if(isset($_POST["borrar"])) {
        
        foreach($mensajesLeidos as $mensaje) {
            $codigos[] = $mensaje["codMensaje"];
        }
        $max = intval(max($codigos));
        
        $codMensajes = [];
        for($i=1; $i<=$max; $i++) {
            if(isset($_POST["mensaje".$i])) {
                $codMensajes[] = $i;
            }
        }
        
        if(!empty($codMensajes)) {
            $ACL->borrarMensajes($codMensajes);
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }
    
    echo "<meta http-equiv='refresh' content='0'>";
}

inicioCabecera("Bicicletas Manolo");
cabecera();
finCabecera();
inicioCuerpo("Bicicletas Manolo");
cuerpo($mensajesSinLeer, $mensajesLeidos);
finCuerpo();

// **********************************************************
function cabecera()
{
    ?>
<link href="/estilos/taller/style.css" rel="stylesheet">
<?php
}

function cuerpo($mensajesSinLeer, $mensajesLeidos)
{
    ?>
<section id="contact" class="section-bg wow fadeInUp">

	<div class="container">

		<div class="section-header">
			<h2>Mensajes</h2>
			<p>Lee los mensajes enviados por visitantes a la página web</p>
		</div>
		
		<?php 
		      tablaMensajesSinLeer($mensajesSinLeer);
		      tablaMensajesLeidos($mensajesLeidos);
		?>
		
		</div>
</section>
<?php
}

function tablaMensajesSinLeer($mensajesSinLeer) {
    ?>
<div class="text-center">
<div class="form">
	<form action="" method="post" role="form" class="contactForm">
		
    	<table align="center">
    		<tr>
    			<th colspan="4" style="font-size: 24px">Mensajes sin leer</th>
    		</tr>
            	
        	<?php
            foreach ($mensajesSinLeer as $mensaje) {
                echo "<tr style='background-color: #fee7ea'>";
                echo "<th>{$mensaje["nombre"]}</th>";
                echo "<th>{$mensaje["correo"]}</th>";
                echo "<th>".Utilidades::fechaSqlANormalGuion($mensaje["fecha"])."</th>";
                echo "<th><input type='checkbox' name='mensaje{$mensaje["codMensaje"]}' value='{$mensaje["codMensaje"]}' title='Marcar como leido'></th>";
                echo "</tr>";
                echo "<tr style='background-color: #d4dbf7'><td colspan='4'><strong>{$mensaje["tema"]}</strong></td></tr>";
                echo "<tr style='background-color: #d4dbf7'><td colspan='4'>{$mensaje["mensaje"]}</td></tr>";
                echo "<tr><td>&nbsp;</td></tr>";
            }
            ?>	
        </table>
        
        <div class="text-center">
			<button type="submit" name="leidos">Marcar leidos</button>
		</div>
    </form>
</div>
<?php
}

function tablaMensajesLeidos($mensajesLeidos) {
    ?>
    <div class="form">
    	<form action="" method="post" role="form" class="contactForm">
        	<table align="center">
        		<tr>
        			<th colspan="4" style="font-size: 24px"><br>Mensajes leidos</th>
        		</tr>
                	
            	<?php
                foreach ($mensajesLeidos as $mensaje) {
                    echo "<tr style='background-color: #fee7ea'>";
                    echo "<th>{$mensaje["nombre"]}</th>";
                    echo "<th>{$mensaje["correo"]}</th>";
                    echo "<th>".Utilidades::fechaSqlANormalGuion($mensaje["fecha"])."</th>";
                    echo "<th><input type='checkbox' name='mensaje{$mensaje["codMensaje"]}' value='{$mensaje["codMensaje"]}' title='Borrar mensaje'></th>";
                    echo "</tr>";
                    echo "<tr style='background-color: #d4dbf7'><td colspan='4'><strong>{$mensaje["tema"]}</strong></td></tr>";
                    echo "<tr style='background-color: #d4dbf7'><td colspan='4'>{$mensaje["mensaje"]}</td></tr>";
                    echo "<tr><td>&nbsp;</td></tr>";
                }
                ?>	
            </table>
            
            <div class="text-center">
    			<button type="submit" name="borrar">Borrar marcados</button>
    		</div>
		</form>
	</div>
</div>
<?php
}