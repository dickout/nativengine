<?php

namespace apps\core\classes;

class ErrorHandler 
{
    private $debug = false;

    public function __construct ($debug) 
    {
        $this->debug = $debug;

        if($this->debug) {
            error_reporting(E_ALL);
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }

        set_error_handler(function ($level, $message, $file, $line, $context) {
            $this->handler(false, $level, $message, $file, $line, $context);
        }, E_ALL);

        register_shutdown_function(function () {
            $this->handler(true);
        });
    }

    private function handler ($shutdown = false, $level = null, $message = null, $file = null, $line = null, $context = null)
    {
        echo $message;
    }
}