<?php

class ACLBD extends ACLBase
{

    private $_conexion;

    private $_conjunto;

    private $_conectado = false;

    private $_error = "";

    private $_errno = 0;

    public function __construct($host, $usuario, $contraseña, $baseDatos)
    {
        $this->_conexion = @new mysqli($host, $usuario, $contraseña, $baseDatos);
        
        $this->_conexion->set_charset("utf8");

        $this->_conectado = true;
        if ($this->_conexion->connect_errno != 0) {
            // hay error
            $this->_conectado = false;
        }
    }

    public function getCodRol($nombre)
    {
        $nombre = Utilidades::limpiarCadena($nombre, 30, true);

        $sentencia = "select codRol from roles where nombre='{$nombre}'";

        $this->_conjunto = $this->_conexion->query($sentencia);

        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);

            foreach ($filas as $fila) {
                return ($fila["codRol"]);
            }
        }
        return false;
    }

    public function getNombreCliente($correo)
    {
        $correo = Utilidades::limpiarCadena($correo, 40, true);

        if (! $this->existeCliente($correo))
            return false;

        $sentencia = "select nombre from clientes where correo = '{$correo}'";

        $this->_conjunto = $this->_conexion->query($sentencia);

        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 2;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);

            foreach ($filas as $fila) {
                return ($fila["nombre"]);
            }
        }

        return false;
    }
    
    public function getCodigoCliente($correo) {
        $correo = Utilidades::limpiarCadena($correo);
        
        if (! $this->existeCliente($correo))
            return false;
            
            $sentencia = "select codCliente from clientes where correo = '{$correo}'";
            
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            $this->_errno = 0;
            if (! $this->_conjunto) {
                $this->_errno = 2;
                return false;
            } else {
                $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
                
                foreach ($filas as $fila) {
                    return ($fila["codCliente"]);
                }
            }
            
            return false;
    }

    public function existeCliente($correo)
    {
        $correo = Utilidades::limpiarCadena($correo, 40, true);

        $sentencia = "select nombre from clientes where correo = '{$correo}'";

        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function existeClienteCodigo($codCliente)
    {
        $codCliente = intval($codCliente);
        
        $sentencia = "select nombre from clientes where codCliente = {$codCliente}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }

    public function anadirRol($nombre, $gestionarBicis, $gestionarClientes, $gestionarArticulos)
    {
        $gestBicis = ($gestionarBicis === false) ? "0" : "1";
        $gestClien = ($gestionarClientes === false) ? "0" : "1";
        $gestArti = ($gestionarArticulos === false) ? "0" : "1";

        $nombre = Utilidades::limpiarCadena($nombre, 30, true);

        if ($this->getcodRol($nombre) !== false)
            return false;

        $sentencia = "insert into roles (nombre,gestionarBicis,gestionarClientes,gestionarArticulos) values('{$nombre}',{$gestBicis},{$gestClien},{$gestArti})";

        $this->_conjunto = $this->_conexion->query($sentencia);

        if (! $this->_conjunto) {
            return false;
        } else {

            return true;
        }
    }

    public function getPermisos($correo, &$gestionarBicis, &$gestionarClientes, &$gestionarArticulos)
    {
        $correo = Utilidades::limpiarCadena($correo, 40, true);

        if (! $this->existeCliente($correo))
            return false;

        $sentencia = "select gestionarBicis, gestionarClientes, gestionarArticulos from roles join clientes using (codRol) where clientes.correo='{$correo}'";

        $this->_conjunto = $this->_conexion->query($sentencia);
        $filas = $this->_conjunto->fetch_assoc();

        $gestionarBicis = $filas["gestionarBicis"];
        $gestionarClientes = $filas["gestionarClientes"];
        $gestionarArticulos = $filas["gestionarArticulos"];
        return true;
    }

    public function existeRol($codRol)
    {
        $codRol = intval($codRol);

        $sentencia = "select codRol from roles where codRol = '{$codRol}'";

        $this->_conjunto = $this->_conexion->query($sentencia);
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }

    public function setNombre($correo, $nombre)
    {
        $nombre = Utilidades::limpiarCadena($nombre, 40, true);
        $correo = Utilidades::limpiarCadena($correo, 40, true);

        if ($this->existeCliente($correo)) {
            $sentencia = "update clientes set nombre = '{$nombre}' WHERE correo = '{$correo}'";
            $this->_conjunto = $this->_conexion->query($sentencia);

            return true;
        } else {
            return false;
        }
    }

    public function esValido($correo, $contraseña)
    {
        $correo = Utilidades::limpiarCadena($correo, 40, false);
        $contraseña = Utilidades::limpiarCadena($contraseña, 40, false);

        if ($this->existeCliente($correo)) {

            $sentencia = "select contrasenia from clientes where correo = '{$correo}' and contrasenia='{$contraseña}'";
            $this->_conjunto = $this->_conexion->query($sentencia);

            return ($this->_conjunto->num_rows == 0 ? false : true);
        } else {
            return false;
        }
    }

    public function anadirCliente($dni_cif, $nombre, $apellidos, $correo, $contraseña, $direccion, $telefono, $codRol)
    {
        $dni_cif = Utilidades::limpiarCadena($dni_cif, 9, true);
        $nombre = Utilidades::limpiarCadena($nombre, 30, false);
        $apellidos = Utilidades::limpiarCadena($apellidos, 50, false);
        $correo = Utilidades::limpiarCadena($correo, 50, false);
        $contraseña = Utilidades::limpiarCadena($contraseña, 50, false);
        $direccion = Utilidades::limpiarCadena($direccion, 100, false);
        $telefono = Utilidades::limpiarCadena($telefono, 12, false);
        $codRol = intval($codRol);

        if (! $this->existeCliente($correo) && $this->existeRol($codRol)) {
            $sentencia = "insert into clientes (dni_cif,nombre,apellidos,correo,contrasenia,direccion,telefono,codRol) values('{$dni_cif}', '{$nombre}', '{$apellidos}', '{$correo}','{$contraseña}', '{$direccion}', '{$telefono}', '{$codRol}')";
            $this->_conjunto = $this->_conexion->query($sentencia);
            if (! $this->_conjunto) {
                return false;
            } else {

                return true;
            }
        } else {
            return false;
        }
    }

    public function dameRoles()
    {
        $sentencia = "select codRol, nombre from roles";
        $this->_conjunto = $this->_conexion->query($sentencia);

        $filas = $this->_conjunto->fetch_all(MYSQLI_NUM);

        $salida = [];
        foreach ($filas as $indice => $value) {
            $salida[$value[0]] = $value[1];
        }

        return $salida;
    }
    
    public function getCodigoRol($rol)
    {
        $rol = Utilidades::limpiarCadena($rol);
        
        $sentencia = "select codRol from roles where nombre='{$rol}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return ($fila["codRol"]);
            }
        }
        return false;
    }
    
    public function getCliente($codCliente) {
        $codCliente = intval($codCliente);
        
        $sentencia = "select * from clientes where codCliente={$codCliente}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }

    public function dameClientes()
    {
        $sentencia = "select * from clientes where borrado = 0";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getClientesFiltrados($campo, $valor, $borrado) {
        $campo = Utilidades::limpiarCadena($campo);
        $valor = Utilidades::limpiarCadena($valor);
        $borrado = intval($borrado);
        
        if($borrado != 0) {
            if($campo == "- Elige un campo -" || $valor == "") {
                $sentencia = "select * from clientes where borrado <> 0";
            }
            else {
                $sentencia = "select * from clientes where {$campo} like '%{$valor}%'";
            }
        }
        else {
            $sentencia = "select * from clientes where {$campo} like '%{$valor}%' and borrado = 0";
        }
        
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
        
    }
    
    public function actualizarCliente($campo, $valor, $codCliente) {
        $campo = Utilidades::limpiarCadena($campo);
        $codCliente = intval($codCliente);
        
        if ($this->existeClienteCodigo($codCliente)) {
            $sentencia = "update clientes set {$campo} = '{$valor}' where codCliente = {$codCliente}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function borrarCliente($codCliente)
    {
        $codCliente = intval($codCliente);
        
        
        if ($this->existeClienteCodigo($codCliente)) {
            $sentencia = "update clientes set borrado=1 where codCliente={$codCliente}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }

    public function actualizarArticulo($campo, $valor, $codArticulo)
    {
        $campo = Utilidades::limpiarCadena($campo);
        $codArticulo = intval($codArticulo);

        if ($this->existeArticulo($codArticulo)) {
            $sentencia = "update articulos set {$campo} = '{$valor}' where codArticulo = {$codArticulo}";
            $this->_conjunto = $this->_conexion->query($sentencia);

            return true;
        } else {
            return false;
        }
    }

    public function borrarArticulo($codArticulo)
    {
        $codArticulo = intval($codArticulo);


        if ($this->existeArticulo($codArticulo)) {
            $sentencia = "update articulos set borrado = 1 where codArticulo = {$codArticulo}";
            $this->_conjunto = $this->_conexion->query($sentencia);

            return true;
        }

        return false;
    }

    public function getCodigoTipoArticulo($tipoArticulo)
    {
        $tipoArticulo = Utilidades::limpiarCadena($tipoArticulo);

        $sentencia = "select codTipoArticulo from tiposarticulos where tipoArticulo='{$tipoArticulo}'";

        $this->_conjunto = $this->_conexion->query($sentencia);

        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);

            foreach ($filas as $fila) {
                return ($fila["codTipoArticulo"]);
            }
        }
        return false;
    }

    public function dameTiposArticulos()
    {
        $sentencia = "select codTipoArticulo, tipoArticulo from tiposarticulos";
        $this->_conjunto = $this->_conexion->query($sentencia);

        $filas = $this->_conjunto->fetch_all(MYSQLI_NUM);

        $salida = [];
        foreach ($filas as $indice => $value) {
            $salida[$value[0]] = $value[1];
        }

        return $salida;
    }

    public function getCodigoArticulo($nombre)
    {
        $nombre = Utilidades::limpiarCadena($nombre);

        $sentencia = "select codArticulo from articulos where nombre='{$nombre}'";

        $this->_conjunto = $this->_conexion->query($sentencia);

        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);

            foreach ($filas as $fila) {
                return ($fila["codArticulo"]);
            }
        }
        return false;
    }

    public function anadirArticulo($codigoReferencia, $nombre, $proveedor, $descripcion, $stock, $precioCompra, $precioVenta, $codigoTipoArticulo)
    {
        $codigoReferencia = Utilidades::limpiarCadena($codigoReferencia, 10, false);
        $nombre = Utilidades::limpiarCadena($nombre, 30, false);
        $proveedor = Utilidades::limpiarCadena($proveedor, 30, false);
        $descripcion = Utilidades::limpiarCadena($descripcion, 250, false);
        $stock = intval($stock);
        $precioCompra = floatval($precioCompra);
        $precioVenta = floatval($precioVenta);
        $codigoTipoArticulo = intval($codigoTipoArticulo);

        if ($this->existeTipoArticulo($codigoTipoArticulo)) {
            $sentencia = "insert into articulos (codReferencia,nombre,proveedor,descripcion,stock,precioCompra,precioVenta,codTipoArticulo) values('{$codigoReferencia}', '{$nombre}', '{$proveedor}', '{$descripcion}','{$stock}', '{$precioCompra}', '{$precioVenta}', '{$codigoTipoArticulo}')";
            $this->_conjunto = $this->_conexion->query($sentencia);
            if (! $this->_conjunto) {
                return false;
            } else {

                return true;
            }
        } else {
            return false;
        }
    }

    public function existeArticulo($codArticulo)
    {
        $codArticulo = intval($codArticulo);

        $sentencia = "select nombre from articulos where codArticulo = '{$codArticulo}'";

        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }

    public function dameArticulos()
    {
        $sentencia = "select * from articulos where  borrado = 0";
        $this->_conjunto = $this->_conexion->query($sentencia);

        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getArticulosFiltrados($campo, $valor, $borrado) {
        $campo = Utilidades::limpiarCadena($campo);
        $valor = Utilidades::limpiarCadena($valor);
        $borrado = intval($borrado);
        
        
        
        if($borrado != 0) {
            if($campo == "- Elige un campo -" || $valor == "") {
                $sentencia = "select * from articulos where borrado <> 0";
            }
            else if($campo == "codTipoArticulo") {
                $sentencia = "select * from articulos where codTipoArticulo in (select codTipoArticulo from tiposarticulos where tipoArticulo like '%{$valor}%')";
            }
            else {
                $sentencia = "select * from articulos where {$campo} like '%{$valor}%'";
            }
        }
        else {
            if($campo == "codTipoArticulo") {
                $sentencia = "select * from articulos where codTipoArticulo in (select codTipoArticulo from tiposarticulos where tipoArticulo like '%{$valor}%') and borrado = 0";
            }
            else {
                $sentencia = "select * from articulos where {$campo} like '%{$valor}%' and borrado = 0";
            }
        }
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
        
    }

    public function getCodigoReferencia($nombre)
    {
        $nombre = Utilidades::limpiarCadena($nombre, true);

        $sentencia = "select codReferencia from articulos where nombre='{$nombre}'";

        $this->_conjunto = $this->_conexion->query($sentencia);

        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);

            foreach ($filas as $fila) {
                return ($fila["codReferencia"]);
            }
        }
        return false;
    }

    public function existeTipoArticulo($codigoTipoArticulo)
    {
        $codigoTipoArticulo = intval($codigoTipoArticulo);

        $sentencia = "select tipoArticulo from tiposarticulos where codTipoArticulo = '{$codigoTipoArticulo}'";

        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }

    public function getNombreArticulo($codArticulo)
    {
        $codArticulo = intval($codArticulo);

        $sentencia = "select nombre from articulos where codArticulo={$codArticulo}";

        $this->_conjunto = $this->_conexion->query($sentencia);

        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 2;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);

            foreach ($filas as $fila) {
                return ($fila["nombre"]);
            }
        }

        return false;
    }
    
    public function getArticulo($codArticulo) {
        $codArticulo = intval($codArticulo);
        
        $sentencia = "select * from articulos where codArticulo={$codArticulo}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $articulo = $this->_conjunto->fetch_assoc();
        
        if(!empty($articulo)) {
            return $articulo;
        }
        
        return false;
    }
    
    public function anadirBicicleta($modelo, $descripcion, $marchas,  $tamanioRuedas, $peso, $material, $codigoTipoBici, $codCliente)
    {
        $modelo = Utilidades::limpiarCadena($modelo, 50, false);
        $descripcion = Utilidades::limpiarCadena($descripcion, 250, false);
        $marchas = intval($marchas);
        $tamanioRuedas = floatval($tamanioRuedas);
        $peso = floatval($peso);
        $material = Utilidades::limpiarCadena($material, 30, false);
        $codigoTipoBici = intval($codigoTipoBici);
        $codCliente = intval($codCliente);
        
        if ($this->existeTipoBici($codigoTipoBici)) {
            $sentencia = "insert into bicicletas (modelo,descripcion,tamanioRuedas,marchas,peso,material,borrado,codTipoBici,codCliente)".
                    " values('{$modelo}', '{$descripcion}', '{$tamanioRuedas}', '{$marchas}', '{$peso}', '{$material}', 0, '{$codigoTipoBici}', '{$codCliente}');";
            
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            $this->_errno = 0;
            if (! $this->_conjunto) {
                $this->_errno = 2;
                return false;
            } else {
                
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function existeTipoBici($codTipoBici) {
        $codTipoBici = intval($codTipoBici);
        
        $sentencia = "select tipo from tiposbicis where codTipoBici = '{$codTipoBici}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function dameBicis()
    {
        $sentencia = "select * from bicicletas where borrado = 0";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
        
    }
    
    public function getBicisFiltrados($campo, $valor, $borrado) {
        $campo = Utilidades::limpiarCadena($campo);
        $valor = Utilidades::limpiarCadena($valor);
        $borrado = intval($borrado);
        
        if($borrado != 0) {
            if($campo == "- Elige un campo -" || $valor == "") {
                $sentencia = "select * from bicicletas where borrado <> 0";
            }
            else if ($campo == "codCliente"){
                $sentencia = "select * from bicicletas where codCliente in (select codCliente from clientes where nombre like '%{$valor}%' or apellidos like '%{$valor}%')";
            }
            else if ($campo == "codTipoBici"){
                $sentencia = "select * from bicicletas where codTipoBici in (select codTipoBici from tiposbicis where tipo like '%{$valor}%')";
            }
            else {
                $sentencia = "select * from bicicletas where {$campo} like '%{$valor}%'";
            }
        }
        else {
            if($campo == "codCliente") {
                $sentencia = "select * from bicicletas where codCliente in (select codCliente from clientes where nombre like '%{$valor}%' or apellidos like '%{$valor}%') and borrado = 0";
            }
            else if ($campo == "codTipoBici"){
                $sentencia = "select * from bicicletas where codTipoBici in (select codTipoBici from tiposbicis where tipo like '%{$valor}%') and borrado = 0";
            }
            else {
                $sentencia = "select * from bicicletas where {$campo} like '%{$valor}%' and borrado = 0";
            }
        }
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
        
    }
    
    public function existeBicicleta($codBici) {
        $codBici = intval($codBici);
        
        $sentencia = "select modelo from bicicletas where codBici = '{$codBici}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function actualizarBicicleta($campo, $valor, $codBici)
    {
        $campo = Utilidades::limpiarCadena($campo);
        $codBici = intval($codBici);
        
        if ($this->existeBicicleta($codBici)) {
            $sentencia = "update bicicletas set {$campo} = '{$valor}' where codBici = {$codBici}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function borrarBicicleta($codBici)
    {
        $codBici = intval($codBici);
        
        if ($this->existeBicicleta($codBici)) {
            $sentencia = "update bicicletas set borrado=1 where codBici={$codBici}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }
    
    public function getNombreTipoBici($codTipoBici) {
        $codTipoBici = intval($codTipoBici);
        
        $sentencia = "select nombre from tiposbicis where codTipoBici={$codTipoBici}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 2;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return ($fila["nombre"]);
            }
        }
        
        return false;
    }
    
    public function dameTiposBicis() {
        $sentencia = "select codTipoBici, tipo from tiposbicis";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $filas = $this->_conjunto->fetch_all(MYSQLI_NUM);
        
        $salida = [];
        foreach ($filas as $indice => $value) {
            $salida[$value[0]] = $value[1];
        }
        
        return $salida;
    }
    
    public function getBicisCliente($codCliente) {
        $codCliente = intval($codCliente);
        
        $sentencia = "select * from bicicletas where codCliente={$codCliente} and borrado=0";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
        
        return $filas;
    }
    
    public function getBicicleta($codBici) {
        $codBici = intval($codBici);
        
        $sentencia = "select * from bicicletas where codBici={$codBici}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $bici = $this->_conjunto->fetch_assoc();
        
        if(!empty($bici)) {
            return $bici;
        }
        
        return false;
    }
    
    public function getNombreApellidoCliente($codCliente) {
        $codCliente = intval($codCliente);
        
        $sentencia = "select nombre, apellidos from clientes where codCliente={$codCliente}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 2;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return $fila;
            }
        }
        
        return false;
    }
    
    public function dameNombreApellidosClientes() {
        $sentencia = "select codCliente, nombre, apellidos from clientes";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $filas = $this->_conjunto->fetch_all(MYSQLI_NUM);
        
        $salida = [];
        foreach ($filas as $indice => $value) {
            $salida[$value[0]] = $value[1].' '.$value[2];
        }
        
        return $salida;
    }
    
    public function getCodigoTipoBicicleta($tipoBici)
    {
        $tipoBici = Utilidades::limpiarCadena($tipoBici);
        
        $sentencia = "select codTipoBici from tiposbicis where tipo='{$tipoBici}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return ($fila["codTipoBici"]);
            }
        }
        return false;
    }
    
    public function anadirEstado($estado, $fecha, $codHojaTrabajo) {
        $estado = Utilidades::limpiarCadena($estado);
        $fecha = Utilidades::limpiarCadena($fecha);
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "insert into estado (estado,fecha,codHojaTrabajo)".
            " values('{$estado}', '{$fecha}', '{$codHojaTrabajo}')";
        $this->_conjunto = $this->_conexion->query($sentencia);
        if (! $this->_conjunto) {
            return false;
        } else {
            return true;
        }
        
    }
    
    public function getEstados($codHojaTrabajo) {
        $sentencia = "select * from estado where codHojaTrabajo={$codHojaTrabajo} order by fecha desc";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function dameHorasTrabajo() {
        $sentencia = "select * from articulos a
                        join tiposarticulos ta using (codTipoArticulo)
                        where tipoArticulo = 'Horas de trabajo'";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function dameComponentes() {
        $sentencia = "select * from articulos a
                        join tiposarticulos ta using (codTipoArticulo)
                        where tipoArticulo = 'Componente' and stock>0";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function crearHojaTrabajo($problema, $codBici) {
        $codBici = intval($codBici);
        $problema = Utilidades::limpiarCadena($problema);
        
        if ($this->existeBicicleta($codBici)) {
            $sentencia = "insert into hojastrabajo (problema,reparada,recogida,fechaApertura,codBici) 
                    values('{$problema}', 0, 0, current_date, {$codBici})";
            $this->_conjunto = $this->_conexion->query($sentencia);
            if (! $this->_conjunto) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function getCodigoHojaTrabajo($codBici) {
        $codBici = intval($codBici);
        
        $sentencia = "select codHojaTrabajo from hojastrabajo where codBici='{$codBici}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function anadirArticuloHojaTrabajo($codArticulo, $codHojaTrabajo, $unidades, $importe) {
        $codArticulo = intval($codArticulo);
        $codHojaTrabajo = intval($codHojaTrabajo);
        $unidades = intval($unidades);
        $importe = floatval($importe);
        
        if ($this->existeArticulo($codArticulo)) {
            $sentencia = "insert into articulos_hojatrabajo (unidades,importe,fecha,codHojaTrabajo,codArticulo) values('{$unidades}', '{$importe}', current_date, '{$codHojaTrabajo}','{$codArticulo}')";
            
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            $this->_conjunto = $this->_conexion->query("SELECT LAST_INSERT_ID();");
            
            $this->_errno = 0;
            if (! $this->_conjunto) {
                $this->_errno = 2;
                return false;
            } else {
                
                $salida = $this->_conjunto->fetch_assoc();
                return $salida["LAST_INSERT_ID()"];
            }
        }
        else {
            return false;
        }
    }
    
    public function anadirHojaTrabajo($codBici, $problema) {
        $codBici = intval($codBici);
        $problema = Utilidades::limpiarCadena($problema);
        
        if ($this->existeBicicleta($codBici)) {
            $sentencia = "insert into hojastrabajo (problema,reparada,recogida,fechaApertura,fechaCierre,codBici)".
                " values('{$problema}', 0, 0, current_date, null, {$codBici});";
            
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            $this->_errno = 0;
            if (! $this->_conjunto) {
                $this->_errno = 2;
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function dameHojasTrabajo() {
        $sentencia = "select * from hojastrabajo order by fechaApertura desc";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getHojasTrabajoFiltrados($campo, $valor) {
        $campo = Utilidades::limpiarCadena($campo);
        $valor = Utilidades::limpiarCadena($valor);
        
        if($campo == "codCliente") {
            $sentencia = "select * from hojasTrabajo where codBici in 
                            (select codBici from bicicletas where codCliente in 
                            (select codCliente from clientes where nombre like '%{$valor}%' or apellidos like '%{$valor}%')) 
                        order by fechaApertura desc";
        }
        else if($campo == "codBici") {
            $sentencia = "select * from hojasTrabajo where codBici in 
                            (select codBici from bicicletas where modelo like '%{$valor}%') 
                        order by fechaApertura desc";
        }
        else {
            $sentencia = "select * from hojasTrabajo where {$campo} like '%{$valor}%' order by fechaApertura desc";
        }
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getImporteTotal($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select importeTotal from importetotalhojatrabajo where codHojaTrabajo={$codHojaTrabajo}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return $fila["importeTotal"];
            }
        }
        return false;
    }
    
    public function getHojaTrabajo($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select * from hojastrabajo where codHojaTrabajo={$codHojaTrabajo}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function actualizarHojaTrabajo($campo, $valor, $codHojaTrabajo)
    {
        $campo = Utilidades::limpiarCadena($campo);
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        if ($this->existeHojaTrabajo($codHojaTrabajo)) {
            if(!isset($valor)) {
                $sentencia = "update hojastrabajo set {$campo} = NULL where codHojaTrabajo = {$codHojaTrabajo}";
            }
            else {
                $sentencia = "update hojastrabajo set {$campo} = '{$valor}' where codHojaTrabajo = {$codHojaTrabajo}";
            }
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function dameArticulosDeHojaTrabajo($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select * from articulos_hojatrabajo where codHojaTrabajo={$codHojaTrabajo} order by fecha desc";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getArticuloHojaTrabajo($codArtHoja) {
        $codArtHoja = intval($codArtHoja);
        
        $sentencia = "select * from articulos_hojatrabajo where codArtHoja={$codArtHoja}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function existeArtHoja($codArtHoja) {
        $codArtHoja = intval($codArtHoja);
        
        $sentencia = "select unidades from articulos_hojatrabajo where codArtHoja = {$codArtHoja}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function actualizarUnidadesArtHoja($codArtHoja, $unidades) {
        $codArtHoja = intval($codArtHoja);
        $unidades = intval($unidades);
        
        $sentencia = "update articulos_hojatrabajo set unidades = {$unidades} where codArtHoja = {$codArtHoja}";
        $this->_conjunto = $this->_conexion->query($sentencia);
    }
    
    public function actualizarImporteArtHoja($codArtHoja, $importe) {
        $codArtHoja = intval($codArtHoja);
        $importe = floatval($importe);
        
        $sentencia = "update articulos_hojatrabajo set importe = {$importe} where codArtHoja = {$codArtHoja}";
        $this->_conjunto = $this->_conexion->query($sentencia);
    }
    
    public function borrarArtHojaTrabajo($codArtHoja) {
        $codArtHoja = intval($codArtHoja);
        
        
        if ($this->existeArtHoja($codArtHoja)) {
            $sentencia = "delete from articulos_hojatrabajo where codArtHoja={$codArtHoja}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }
    
    public function existeHojaTrabajo($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select fechaApertura from hojastrabajo where codHojaTrabajo = '{$codHojaTrabajo}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function crearFactura($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        if ($this->existeHojaTrabajo($codHojaTrabajo)) {
            $sentencia = "insert into facturas (fecha, codHojaTrabajo) values(current_date, {$codHojaTrabajo})";
            $this->_conjunto = $this->_conexion->query($sentencia);
            if (! $this->_conjunto) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function getFactura($codFactura) {
        $codFactura = intval($codFactura);
        
        $sentencia = "select * from facturas where codFactura={$codFactura}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function getFacturaConHoja($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select * from facturas where codHojaTrabajo = {$codHojaTrabajo}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function dameFacturas() {
        $sentencia = "select * from facturas order by fecha desc";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function existeFactura($codFactura) {
        $codFactura = intval($codFactura);
        
        $sentencia = "select codFactura from facturas where codFactura = '{$codFactura}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function existeFacturaDeHoja($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select codFactura from facturas where codHojaTrabajo = {$codHojaTrabajo}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function getFacturasFiltradas($campo, $valor) {
        $campo = Utilidades::limpiarCadena($campo);
        $valor = Utilidades::limpiarCadena($valor);
        
        if($campo == "codCliente") {
            $sentencia = "select * from facturas where codHojaTrabajo in 
                        (select codHojaTrabajo from hojastrabajo where codBici in 
                        (select codBici from bicicletas where codCliente in 
                        (select codCliente from clientes where nombre like '%{$valor}%' or apellidos like '%{$valor}%'))) 
                    order by fecha desc";
        }
        else if($campo == "codBici") {
            $sentencia = "select * from facturas where codHojaTrabajo in 
                            (select codHojaTrabajo from hojastrabajo where codBici in 
                            (select codBici from bicicletas where modelo like '%{$valor}%')) 
                        order by fecha desc";
        }
        else {
            $sentencia = "select * from facturas where {$campo} like '%{$valor}%'";
        }
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function anadirArticuloFactura($unidades, $importeBase, $iva, $descuento, $importeFinal, $codArticulo, $codFactura, $codArtHoja) {
        $unidades = intval($unidades);
        $importeBase = floatval($importeBase);
        $iva = floatval($iva);
        $descuento = floatval($descuento);
        $importeFinal = floatval($importeFinal);
        $descuento = floatval($descuento);
        $codArticulo = intval($codArticulo);
        $codFactura = intval($codFactura);
        $codArtHoja = intval($codArtHoja);
        
        if ($this->existeArticulo($codArticulo)) {
            $sentencia = "insert into articulos_factura (unidades,importeBase,iva,descuento,importeFinal,codArticulo,codFactura,codArtHoja) values({$unidades}, {$importeBase}, {$iva}, {$descuento}, {$importeFinal}, {$codArticulo}, {$codFactura}, {$codArtHoja})";
            
            $this->_conjunto = $this->_conexion->query($sentencia);
            if (! $this->_conjunto) {
                return false;
            } else {
                
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function dameArticulosDeFactura($codFactura) {
        $codFactura = intval($codFactura);
        
        $sentencia = "select * from articulos_factura where codFactura={$codFactura}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getImporteBaseTotal($codFactura) {
        $codFactura = intval($codFactura);
        
        $sentencia = "select importeBaseTotal from importetotalfactura where codFactura={$codFactura}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return $fila["importeBaseTotal"];
            }
        }
        return false;
    }
    
    public function getImporteFinalTotal($codFactura) {
        $codFactura = intval($codFactura);
        
        $sentencia = "select importeFinalTotal from importetotalfactura where codFactura={$codFactura}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return $fila["importeFinalTotal"];
            }
        }
        return false;
    }
    
    public function getArticuloFactura($codArticuloFactura) {
        $codArticuloFactura = intval($codArticuloFactura);
        
        $sentencia = "select * from articulos_factura where codArticuloFactura={$codArticuloFactura}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function existeArtFactura($codArtFactura) {
        $codArtFactura = intval($codArtFactura);
        
        $sentencia = "select unidades from articulos_factura where codArticuloFactura = {$codArtFactura}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function existeArtFacturaConArtHoja($codArtHoja) {
        $codArtHoja = intval($codArtHoja);
        
        $sentencia = "select unidades from articulos_factura where codArtHoja = {$codArtHoja}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function actualizarArticuloFactura($campo, $valor, $codArticuloFactura) {
        $campo = Utilidades::limpiarCadena($campo);
        $codArticuloFactura = intval($codArticuloFactura);
        
        if ($this->existeArtFactura($codArticuloFactura)) {
            $sentencia = "update articulos_factura set {$campo} = '{$valor}' where codArticuloFactura = {$codArticuloFactura}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function actualizarArticuloFacturaDesdeHoja($campo, $valor, $codArtHoja) {
        $campo = Utilidades::limpiarCadena($campo);
        $codArtHoja = intval($codArtHoja);
        
        if ($this->existeArtFacturaConArtHoja($codArtHoja)) {
            $sentencia = "update articulos_factura set {$campo} = '{$valor}' where codArtHoja = {$codArtHoja}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function borrarArtFactura($codArtFactura) {
        $codArtFactura = intval($codArtFactura);
        
        if ($this->existeArtFactura($codArtFactura)) {
            $sentencia = "delete from articulos_factura where codArticuloFactura={$codArtFactura}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }
    
    public function borrarArtFacturaConArtHoja($codArtHoja) {
        $codArtHoja = intval($codArtHoja);
        
        if ($this->existeArtFacturaConArtHoja($codArtHoja)) {
            $sentencia = "delete from articulos_factura where codArtHoja={$codArtHoja}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }
    
    
    public function anadirMensaje($nombre, $correo, $tema, $mensaje, $cliente) {
        $nombre = Utilidades::limpiarCadena($nombre);
        $correo = Utilidades::limpiarCadena($correo);
        $tema = Utilidades::limpiarCadena($tema);
        $mensaje = Utilidades::limpiarCadena($mensaje);
        
        $sentencia = "insert into mensajes(fecha,nombre,correo,tema,mensaje,cliente) ".
            "values(current_date, '{$nombre}', '{$correo}', '{$tema}', '{$mensaje}', {$cliente})";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        if (! $this->_conjunto) {
            return false;
        } 
        else {
            return true;
        }
        
    }
    
    public function marcarMensajesComoLeidos($codMensajes) {
        if(is_array($codMensajes)) {
            if(!empty($codMensajes)) {
                foreach($codMensajes as $clave=>$valor) {
                    $sentencia = "update mensajes set leido = 1 where codMensaje = {$valor}";
                    $this->_conjunto = $this->_conexion->query($sentencia);
                }
            }
        }
        
    }
    
    public function dameMensajesSinLeer() {
        $sentencia = "select * from mensajes
                        where leido = 0 order by fecha desc";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function dameMensajesLeidos() {
        $sentencia = "select * from mensajes
                        where leido <> 0 order by fecha desc";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function borrarMensajes($codMensajes) {
        if(is_array($codMensajes)) {
            if(!empty($codMensajes)) {
                foreach($codMensajes as $clave=>$valor) {
                    $sentencia = "delete from mensajes where codMensaje={$valor}";
                    $this->_conjunto = $this->_conexion->query($sentencia);
                }
            }
        }
    }
    
    public function getImporteTotalObraMano($codHojaTrabajo) {
        $codHojaTrabajo = intval($codHojaTrabajo);
        
        $sentencia = "select importetotaltrabajo from importetotalmanoobra where codHojaTrabajo={$codHojaTrabajo}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 1;
            return false;
        } else {
            $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
            
            foreach ($filas as $fila) {
                return $fila["importetotaltrabajo"];
            }
        }
        return false;
    }
    
    public function getEstado($codEstado) {
        $codEstado = intval($codEstado);
        
        $sentencia = "select * from estado where codEstado={$codEstado}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function existeEstado($codEstado) {
        $codEstado = intval($codEstado);
        
        $sentencia = "select estado from estado where codEstado = {$codEstado}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function actualizarEstado($campo, $valor, $codEstado) {
        $campo = Utilidades::limpiarCadena($campo);
        $codEstado = intval($codEstado);
        
        if ($this->existeEstado($codEstado)) {
            $sentencia = "update estado set {$campo} = '{$valor}' where codEstado = {$codEstado}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function borrarEstado($codEstado)
    {
        $codEstado = intval($codEstado);
        
        if ($this->existeEstado($codEstado)) {
            $sentencia = "delete from estado where codEstado={$codEstado}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }
    
    
    

    public function anadirPresupuesto($codBici, $descripcion) {
        $codBici = intval($codBici);
        $descripcion = Utilidades::limpiarCadena($descripcion);
        
        if ($this->existeBicicleta($codBici)) {
            $sentencia = "insert into presupuestos (descripcion,codBici)".
                " values('{$descripcion}', {$codBici});";
            
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            $this->_conjunto = $this->_conexion->query("SELECT LAST_INSERT_ID();");
            
            $this->_errno = 0;
            if (! $this->_conjunto) {
                $this->_errno = 2;
                return false;
            } else {
                
                $salida = $this->_conjunto->fetch_assoc();
                return $salida["LAST_INSERT_ID()"];
            }
        }
        else {
            return false;
        }
    }
    
    public function existePresupuesto($codPresupuesto) {
        $codPresupuesto = intval($codPresupuesto);
        
        $sentencia = "select codPresupuesto from presupuestos where codPresupuesto = '{$codPresupuesto}'";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function getPresupuestosCliente($codBici) {
        $codBici = intval($codBici);
        
        $sentencia = "select * from presupuestos where codBici={$codBici}";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $filas = $this->_conjunto->fetch_all(MYSQLI_ASSOC);
        
        return $filas;
    }
    
    public function getImportePresupuesto($codPresupuesto) {
        $codPresupuesto = intval($codPresupuesto);
        
        $sentencia = "select precioTotalCalculado, precioPresupuesto from importetotalpresupuesto where codPresupuesto={$codPresupuesto}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function damePresupuestos() {
        $sentencia = "select * from presupuestos";
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getPresupuestosFiltrados($campo, $valor) {
        $campo = Utilidades::limpiarCadena($campo);
        $valor = Utilidades::limpiarCadena($valor);
        
        if($campo == "codCliente") {
            $sentencia = "select * from presupuestos where codBici in (select codBici from bicicletas where codCliente in (select codCliente from clientes where nombre like '%{$valor}%' or apellidos like '%{$valor}%'))";
        }
        else if ($campo == "codBici") {
            $sentencia = "select * from presupuestos where codBici in (select codBici from bicicletas where modelo like '%{$valor}%')";
        }
        else {
            $sentencia = "select * from presupuestos where {$campo} like '%{$valor}%'";
        }
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getPresupuesto($codPresupuesto) {
        $codPresupuesto = intval($codPresupuesto);
        
        $sentencia = "select * from presupuestos where codPresupuesto={$codPresupuesto}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function anadirArticuloPresupuesto($codArticulo, $codPresupuesto, $unidades, $importeFinal) {
        $codArticulo = intval($codArticulo);
        $codPresupuesto = intval($codPresupuesto);
        $unidades = intval($unidades);
        $importeFinal = floatval($importeFinal);
        
        if ($this->existeArticulo($codArticulo)) {
            $sentencia = "insert into articulos_presupuestos (unidades,importeFinal,codPresupuesto,codArticulo) values({$unidades}, {$importeFinal}, {$codPresupuesto},{$codArticulo})";
            $this->_conjunto = $this->_conexion->query($sentencia);
            if (! $this->_conjunto) {
                return false;
            } else {
                
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function getArticulosPresupuesto($codPresupuesto) {
        $codPresupuesto = intval($codPresupuesto);
        
        $sentencia = "select * from articulos_presupuestos where codPresupuesto={$codPresupuesto}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = [];
        while($obj = $this->_conjunto->fetch_assoc()) {
            $salida[] = $obj;
        }
        
        return $salida;
    }
    
    public function getArticuloPresupuesto($codArticuloPresupuesto) {
        $codArticuloPresupuesto = intval($codArticuloPresupuesto);
        
        $sentencia = "select * from articulos_presupuestos where codArticuloPresupuesto={$codArticuloPresupuesto}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        
        $salida = $this->_conjunto->fetch_assoc();
        
        if(!empty($salida)) {
            return $salida;
        }
        
        return false;
    }
    
    public function existeArtPresupuesto($codArtPresupuesto) {
        $codArtPresupuesto = intval($codArtPresupuesto);
        
        $sentencia = "select unidades from articulos_presupuestos where codArticuloPresupuesto = {$codArtPresupuesto}";
        
        $this->_conjunto = $this->_conexion->query($sentencia);
        $this->_errno = 0;
        if (! $this->_conjunto) {
            $this->_errno = 3;
            return false;
        }
        return ($this->_conjunto->num_rows == 0) ? false : true;
    }
    
    public function actualizarUnidadesArtPresupuesto($codArtPresupuesto, $unidades) {
        $codArtPresupuesto = intval($codArtPresupuesto);
        $unidades = intval($unidades);
        
        $sentencia = "update articulos_presupuestos set unidades = {$unidades} where codArticuloPresupuesto = {$codArtPresupuesto}";
        $this->_conjunto = $this->_conexion->query($sentencia);
    }
    
    public function actualizarImporteArtPresupuesto($codArtPresupuesto, $importe) {
        $codArtPresupuesto = intval($codArtPresupuesto);
        $importe = floatval($importe);
        
        $sentencia = "update articulos_presupuestos set importe = '{$importe}' where codArticuloPresupuesto = {$codArtPresupuesto}";
        $this->_conjunto = $this->_conexion->query($sentencia);
    }
    
    public function borrarArtPresupuesto($codArtPresupuesto) {
        $codArtPresupuesto = intval($codArtPresupuesto);
        
        
        if ($this->existeArtHoja($codArtPresupuesto)) {
            $sentencia = "delete from articulos_presupuestos where codArticuloPresupuesto={$codArtPresupuesto}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        
        return false;
    }
    
    public function borrarPrespuesto($codPresupuesto) {
        $codPresupuesto = intval($codPresupuesto);
        
        if($this->existePresupuesto($codPresupuesto)) {
            $sentencia = "delete from articulos_presupuestos where codPresupuesto={$codPresupuesto}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            $sentencia = "delete from presupuestos where codPresupuesto={$codPresupuesto}";
            $this->_conjunto = $this->_conexion->query($sentencia);
            
            return true;
        }
        return false;
        
    }
    
}