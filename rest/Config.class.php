<?php

//DB CONNECTION
class Config {
    public static function DB_HOST(){
        return 'localhost';
    }

    public static function DB_USERNAME(){
        return 'root';
    }

    public static function DB_PASSWORD(){
        return 'root';
    }

    public static function DB_SCHEMA(){
        return 'studyzone';
    }
}

// JWT Secret
define('JWT_SECRET', 'hgY=&*54#T+kTe,selma8zT=7L-3z4tV/&9');
?>