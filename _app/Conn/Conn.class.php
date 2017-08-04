<?php

/**
 * Classe responsável por conexao no banco de dados
 * 
 * @copyright (c) 2016, Adão Ferreira
 */
class Conn {

    private static $Host = 'localhost';
    private static $DBsa = 'calendario';
    private static $User = 'root';
    private static $Pass = '';
    
    /** @var PDO */
    private static $Connect;
    
    private static function Conectar() {
        try {
            if(self::$Connect == null):
                $dsn = "mysql:host=".self::$Host.";dbname=".self::$DBsa;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
            endif;
        } catch (PDOException $e) {
            echo $e->getTraceAsString();
        }
        
        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }
    
    public static function getConn(){
        return self::Conectar();
    }   
}
