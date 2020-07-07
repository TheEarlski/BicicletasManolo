<?php

class Acceso
{
    private $_correo="";
    private $_nombre="";
    private $_gestionarBicicletas=false;
    private $_gestionarArticulos=false;
    private $_gestionarClientes=false;
    private $_validado=false;
    
    public function __construct()
    {
        $this->recogerDatosSesion();
        
    }
    
    public function registrarUsuario($correo, $nombre, $gestionarBici, $gestionarCli, $gestionarArti)
    {
        $this->_validado=true;
        $this->_correo=$correo;
        $this->_nombre=$nombre;
        $this->_gestionarBicicletas=$gestionarBici;
        $this->_gestionarClientes=$gestionarCli;
        $this->_gestionarArticulos=$gestionarArti;
        
        $this->escribirDatosSesion();
        return true;
    }
    public function quitarRegistroUsuario()
    {
        $this->_validado=false;
        $this->_correo="";
        $this->_nombre="";
        $this->_gestionarBicicletas=false;
        $this->_gestionarArticulos=false;
        $this->_gestionarClientes=false;
        
        $this->escribirDatosSesion();
        return true;
    }
    
    
    private function recogerDatosSesion()
    {
        $this->_validado=false;
        $this->_correo="";
        $this->_nombre="";
        $this->_gestionarBicicletas=false;
        $this->_gestionarArticulos=false;
        $this->_gestionarClientes=false;
        
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        if (!isset($_SESSION["acceso"]))
            $_SESSION["acceso"]=["validado"=>false,
                "correo"=>"",
                "nombre"=>"",
                "gestionarBicicletas"=>false,
                "gestionarClientes"=>false,
                "gestionarArticulos"=>false
            ];
            
            if (isset($_SESSION["acceso"]) &&
                $_SESSION["acceso"]["validado"]==true)
            {
                $this->_validado=true;
                $this->_correo=$_SESSION["acceso"]["correo"];
                $this->_nombre=$_SESSION["acceso"]["nombre"];
                $this->_gestionarBicicletas=$_SESSION["acceso"]["gestionarBicicletas"];
                $this->_gestionarClientes=$_SESSION["acceso"]["gestionarClientes"];
                $this->_gestionarArticulos=$_SESSION["acceso"]["gestionarArticulos"];
            }
            
            return true;
    }
    
    private function escribirDatosSesion()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        $_SESSION["acceso"]=["validado"=>$this->_validado,
            "correo"=>$this->_correo,
            "nombre"=>$this->_nombre,
            "gestionarBicicletas"=>$this->_gestionarBicicletas,
            "gestionarClientes"=>$this->_gestionarClientes,
            "gestionarArticulos"=>$this->_gestionarArticulos
        ];
        
        return true;
    }
    
    public function hayUsuario()
    {
        return $this->_validado;
    }
    
    public function gestionarBicicletas()
    {
        return $this->_gestionarBicicletas;
    }
    
    public function gestionarClientes()
    {
        return $this->_gestionarClientes;
    }
    
    public function gestionarArticulos() {
        return $this->_gestionarArticulos;
    }
    
    public function getCorreo()
    {
        return $this->_correo;
    }
    
    public function getNombre()
    {
        return $this->_nombre;
    }
    
    
    
}