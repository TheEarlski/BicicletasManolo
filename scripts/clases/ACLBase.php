<?php
abstract class ACLBase {
    
    /*
     * Permite añadir un nuevo role con el nombre y los permisos indicados.
     * Devuelve true si se ha podido añadir el role y false en caso de que
     * no (ya existe otro role con el mismo nombre).
     */
    abstract public function anadirRol($nombre, $gestionarBicis, $gestionarClientes, $gestionarArticulos);
    
    /*
     * Devuelve el código de role correspondiente al $nombre o false si no
     * lo encuentra).
     */
    abstract public function getCodRol($nombre);
    
    /*
     * Devuelve true si existe un role con el código indicado.
     */
    abstract public function existeRol($codRol);
    
    /*
     * Añade un nuevo usuario con los datos indicados. Devuelve true si lo
     * ha podido crear o falso en caso de error (Nick repetido, codRole que
     * no existe, etc). El Nick debe ser único.
     */
    abstract public function anadirCliente($dni_cif, $nombre, $apellidos, $correo, $contraseña, $direccion, $telefono, $codRole);
    
    /*
     * Devuelve true si existe un usuario con el Nick indicado.
     */
    abstract public function existeCliente($correo);
    
    /*
     * Comprueba dentro de la ACL disponible si el $nick (usuario) existe
     * y coincide su contraseña. Devuelve true si es válido y false en otro
     * caso.
     */
    abstract public function esValido($correo, $contraseña);
    
    /*
     * Función que dado un $usuario devuelve sus permisos como parámetros.
     * La función devuelve true si el usuario existe y false en caso contrario.
     */
    abstract public function getPermisos($correo, &$gestionarBicis, &$gestionarClientes, &$gestionarArticulos);
    
    /*
     * Función que devuelve el nombre dado un $nick.
     */
    abstract public function getNombreCliente($correo);
    
    /*
     * Función que permite cambiar el $nombre de un usuario identificado por
     * el $nick
     */
    abstract public function setNombre($correo, $nombre);
    
    /*
     * Esta función devuelve un array asociativo donde el indice es el nick
     * y el dato es el nombre.
     */
    abstract public function dameClientes();
    
    /*
     * Esta función devuelve un array indexado en la que el índice es el
     * código de role y el valor el nombre.
     */
    abstract public function dameRoles();
    
    abstract public function getCodigoCliente($correo);
    
    abstract public function existeClienteCodigo($codCliente);
    
    abstract public function getCliente($codCliente);
    
    abstract public function getClientesFiltrados($campo, $valor, $borrado);
        
    abstract public function actualizarCliente($campo, $valor, $codCliente);
        
    abstract public function borrarCliente($codCliente);
        
    abstract public function actualizarArticulo($campo, $valor, $codArticulo);
        
    abstract public function borrarArticulo($codArticulo);
        
    abstract public function getCodigoTipoArticulo($tipoArticulo);
        
    abstract public function dameTiposArticulos();
        
    abstract public function getCodigoArticulo($nombre);
    
    abstract public function anadirArticulo($codigoReferencia, $nombre, $proveedor, $descripcion, $stock, $precioCompra, $precioVenta, $codigoTipoArticulo);
        
    abstract public function existeArticulo($codArticulo);
        
    abstract public function dameArticulos();
        
    abstract public function getArticulosFiltrados($campo, $valor, $borrado);
        
    abstract public function getCodigoReferencia($nombre);
        
    abstract public function existeTipoArticulo($codigoTipoArticulo);
        
    abstract public function getNombreArticulo($codArticulo);
        
    abstract public function getArticulo($codArticulo);
        
    abstract public function anadirBicicleta($modelo, $descripcion, $marchas,  $tamanioRuedas, $peso, $material, $codigoTipoBici, $codCliente);
        
    abstract public function existeTipoBici($codTipoBici);
        
    abstract public function dameBicis();
        
    abstract public function getBicisFiltrados($campo, $valor, $borrado);
        
    abstract public function existeBicicleta($codBici);
        
    abstract public function actualizarBicicleta($campo, $valor, $codBici);
        
    abstract public function borrarBicicleta($codBici);
        
    abstract public function getNombreTipoBici($codTipoBici);
        
    abstract public function dameTiposBicis();
        
    abstract public function getBicisCliente($codCliente);
        
    abstract public function getBicicleta($codBici);
        
    abstract public function getNombreApellidoCliente($codCliente);
        
    abstract public function dameNombreApellidosClientes();
        
    abstract public function getCodigoTipoBicicleta($tipoBici);
        
    abstract public function getEstados($codHojaTrabajo);
        
    abstract public function dameHorasTrabajo();
        
    abstract public function dameComponentes();
        
    abstract public function crearHojaTrabajo($problema, $codBici);
        
    abstract public function getCodigoHojaTrabajo($codBici);
        
    abstract public function anadirArticuloHojaTrabajo($codArticulo, $codHojaTrabajo, $unidades, $importe);
        
    abstract public function anadirHojaTrabajo($codBici, $problema);
        
    abstract public function dameHojasTrabajo();
        
    abstract public function getHojasTrabajoFiltrados($campo, $valor);
        
    abstract public function getImporteTotal($codHojaTrabajo);
        
    abstract public function getHojaTrabajo($codHojaTrabajo);
        
    abstract public function actualizarHojaTrabajo($campo, $valor, $codHojaTrabajo);
        
    abstract public function dameArticulosDeHojaTrabajo($codHojaTrabajo);
        
    abstract public function getArticuloHojaTrabajo($codArtHoja);
        
    abstract public function existeArtHoja($codArtHoja);
        
    abstract public function actualizarUnidadesArtHoja($codArtHoja, $unidades);
        
    abstract public function actualizarImporteArtHoja($codArtHoja, $importe);
        
    abstract public function borrarArtHojaTrabajo($codArtHoja);
        
    abstract public function existeHojaTrabajo($codHojaTrabajo);
        
    abstract public function crearFactura($codHojaTrabajo);
        
    abstract public function getFactura($codFactura);
        
    abstract public function getFacturaConHoja($codHojaTrabajo);
        
    abstract public function dameFacturas();
        
    abstract public function existeFactura($codFactura);
        
    abstract public function existeFacturaDeHoja($codHojaTrabajo);
        
    abstract public function getFacturasFiltradas($campo, $valor);
        
    abstract public function anadirArticuloFactura($unidades, $importeBase, $iva, $descuento, $importeFinal, $codArticulo, $codFactura, $codArtHoja);
        
    abstract public function dameArticulosDeFactura($codFactura);
        
    abstract public function getImporteBaseTotal($codFactura);
        
    abstract public function getImporteFinalTotal($codFactura);
        
    abstract public function getArticuloFactura($codArticuloFactura);
        
    abstract public function existeArtFactura($codArtFactura);
        
    abstract public function existeArtFacturaConArtHoja($codArtHoja);
        
    abstract public function actualizarArticuloFactura($campo, $valor, $codArticuloFactura);
        
    abstract public function actualizarArticuloFacturaDesdeHoja($campo, $valor, $codArtHoja);
        
    abstract public function borrarArtFactura($codArtFactura);
        
    abstract public function borrarArtFacturaConArtHoja($codArtHoja);
        
    abstract public function anadirMensaje($nombre, $correo, $tema, $mensaje, $cliente);
        
    abstract public function marcarMensajesComoLeidos($codMensajes);
        
    abstract public function dameMensajesSinLeer();
        
    abstract public function dameMensajesLeidos();
        
    abstract public function borrarMensajes($codMensajes);
        
    abstract public function getImporteTotalObraMano($codHojaTrabajo);
        
    abstract public function getEstado($codEstado);
        
    abstract public function existeEstado($codEstado);
        
    abstract public function actualizarEstado($campo, $valor, $codEstado);
        
    abstract public function borrarEstado($codEstado);
        
    abstract  public function anadirPresupuesto($codBici, $descripcion);
        
    abstract public function existePresupuesto($codPresupuesto);
        
    abstract public function getPresupuestosCliente($codBici);
        
    abstract public function getImportePresupuesto($codPresupuesto);
        
    abstract public function damePresupuestos();
        
    abstract public function getPresupuestosFiltrados($campo, $valor);
        
    abstract public function getPresupuesto($codPresupuesto);
        
    abstract public function anadirArticuloPresupuesto($codArticulo, $codPresupuesto, $unidades, $importeFinal);
        
    abstract public function getArticulosPresupuesto($codPresupuesto);
        
    abstract public function getArticuloPresupuesto($codArticuloPresupuesto);
        
    abstract public function existeArtPresupuesto($codArtPresupuesto);
        
    abstract public function actualizarUnidadesArtPresupuesto($codArtPresupuesto, $unidades);
        
    abstract public function actualizarImporteArtPresupuesto($codArtPresupuesto, $importe);
        
    abstract public function borrarArtPresupuesto($codArtPresupuesto);
        
    abstract public function borrarPrespuesto($codPresupuesto);
    
}