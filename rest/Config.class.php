<?php
//Set the reporting
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));


class Config {

    public static function DB_NAME() {
        return Config::get_env("DB_NAME", "studyzone");
    }

    public static function DB_PORT() {
        return Config::get_env("DB_PORT", "3306");
    }

    public static function DB_PASSWORD() {
        return Config::get_env("DB_PASSWORD", "root");
    }

    public static function DB_HOST() {
        return Config::get_env("DB_HOST", "127.0.0.1");
    }

    public static function DB_USERNAME() {
        return Config::get_env("DB_USERNAME", "root");
    }

    public static function JWT_SECRET() {
        return Config::get_env("JWT_SECRET", "hgY=&*54#T+kTe,selma8zT=7L-3z4tV/&9");
    }

    public static function get_env($name, $default) {
        return isset($_ENV[$name]) && trim($_ENV[$name])!= "" ? $_ENV[$name] : $default;
    }
}

/*
//DB Connection
define('DB_NAME', 'studyzone');
define('DB_PORT', '3306');
define('DB_PASSWORD', 'root');
define('DB_HOST', '127.0.0.1');
define('DB_USERNAME', 'root'); 

// JWT Secret
define('JWT_SECRET', 'hgY=&*54#T+kTe,selma8zT=7L-3z4tV/&9'); 
*/
?>