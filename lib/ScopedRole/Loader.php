<?php

namespace ScopedRole;

class Loader {

    /**
     * SPL autoloader function
     * @param string $class
     */
    public static function load($class)
    {
        $class = ltrim($class, "\\");
        if (0 !== strpos($class, 'ScopedRole\\')) {
            return;
        }
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $path .= str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
        require $path;
    }

    /**
     * Register autoloader for ScopedRole classes/interfaces
     */
    public static function register()
    {
        spl_autoload_register(array('ScopedRole\\Loader', 'load'));
    }
}