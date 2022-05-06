<?php

function autoload($classname)
{
    if (file_exists($file = dirname (__FILE__, 2) . '/class/' . $classname . '.class.php')) {
        require $file;
    }
}

spl_autoload_register('autoload');