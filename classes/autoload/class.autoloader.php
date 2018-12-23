<?php

class Autoloader {

    static public function loader($class) {
        $filename = "classes/" . str_replace("\\", '/class.', strtolower($class)) . ".php";
        if (file_exists($filename) && is_readable($filename)) {
            require_once($filename);
            if (class_exists($class)) {
                return true;
            }
        }
        return false;
    }

}

spl_autoload_register('Autoloader::loader');
