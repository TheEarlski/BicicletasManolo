<?php
require(dirname(__FILE__) . "/../../scripts/clases/PHPMailer/PHPMailerAutoload.php");

class Utilidades{
    static public function limpiarCadena($cadena,$longitud=1000,$aMayusula=false,$escapar=true)
    {
        $cadena=mb_substr($cadena,0,$longitud);
        if ($aMayusula)
            $cadena=mb_strtoupper($cadena);
            
            if ($escapar)
            {
                $cadena=str_replace("'", "''", $cadena);
            }
            return $cadena;
    }
    
    static public function fechaSqlANormal($fechaSQL) {
        $fechaDividida = explode("-", $fechaSQL);
        return $fechaDividida[2]."/".$fechaDividida[1]."/".$fechaDividida[0];
    }
    
    static public function fechaSqlANormalGuion($fechaSQL) {
        $fechaDividida = explode("-", $fechaSQL);
        return $fechaDividida[2]."-".$fechaDividida[1]."-".$fechaDividida[0];
    }
    
    static public function fechaNormalASQL($fechaNormal) {
        if(strpos($fechaNormal, "-") !== false) {
            $fechaDividida = explode("-", $fechaNormal); 
        }
        else if(strpos($fechaNormal, "/") !== false) {
            $fechaDividida = explode("/", $fechaNormal); 
        }
        
        if(count($fechaDividida) == 2) {
            return $fechaDividida[1]."-".$fechaDividida[0];
        }
        else {
            return $fechaDividida[2]."-".$fechaDividida[1]."-".$fechaDividida[0];
        }
    }
    
    static public function enviarCorreo($destino, $nombre, $sujeto, $mensaje) {
        $mail = new PHPMailer();
        
        $nombre = mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
        
        try {
            
            $mail->setFrom('proyectobicicletasmanolo@gmail.com', 'Bicicletas Manolo');
            $mail->addAddress($destino, $nombre);
            $mail->Subject = $sujeto;
            $mail->Body = $mensaje;
            
            /* SMTP parameters. */
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = TRUE;
            $mail->SMTPSecure = 'tls';
            $mail->Username = 'proyectobicicletasmanolo@gmail.com';
            $mail->Password = 'BicisManolo2020';
            $mail->Port = 587;
            
            /* Disable some SSL checks. */
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            /* Finally send the mail. */
            $mail->send();
            
            
        }
        catch (Exception $e)
        {
            echo $e->errorMessage();
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    
}