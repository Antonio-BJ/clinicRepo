<?php 
 ini_set("default_socket_timeout", 2);
require_once "config.php";

class Modelo
{
    public $_db;
    
    public function __construct()
    {

        $this->_db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         //conexion Zima
        // $this->_dbZima = new PDO("mysql:host=".DB_HOSTZ.";dbname=".DB_NAMEZ, DB_USERZ, DB_PASSZ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        // $this->_dbZima->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Conexion MV externos
        $this->_dbExternos = new PDO("mysql:host=".DB_HOSTE.";dbname=".DB_NAMEE, DB_USERE, DB_PASSE,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         //conexion AMI
        // $this->_dbAMI = new PDO("mysql:host=".DB_HOSTA.";dbname=".DB_NAMEA, DB_USERA, DB_PASSA,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        // $this->_dbAMI->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
}

?>
