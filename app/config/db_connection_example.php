<?php

namespace Frame\Configuration;

class Database {

    private static $instance;
    private const USER = 'root';
    private const PASS = '';
    private const DRIVER = 'mysql';
    private const HOST = 'localhost';
    private const DBNAME = 'monza_mobilita_app';
    private const PORT = "3306";
    private const CHARSET = 'utf8mb4';

    private static array $options = [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
    ];


    public static function singleton()
    {   
        if(!isset(static::$instance)){
            try {
                static::$instance = new \PDO(
                    static::resolveDsn(), 
                    static::USER, 
                    static::PASS, 
                    static::$options
                );
    
            } catch(\PDOException $e) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                echo '500 Internal Server Error';
                die();
            }
        }
        return static::$instance;
    }

    private static function resolveDsn()
    {
        return (
            static::DRIVER . 
            ':host=' . static::HOST . 
            ';dbname=' . static::DBNAME . 
            ';charset=' . static::CHARSET . 
            ';port=' . static::PORT
        );
    }

}
