<?php 

require_once "config.php";
class Modelo
{
    protected $_db;
    
    public function __construct()
    {

        
        $this->_db_zima = new PDO("mysql:host=".DB_HOST_ZIMA.";dbname=".DB_NAME_ZIMA, DB_USER_ZIMA, DB_PASS_ZIMA,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->_db_zima->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->_db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
}

?>
