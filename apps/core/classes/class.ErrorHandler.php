<?php

namespace apps\core\classes;

use apps\core\classes\FileManager;

class ErrorHandler 
{
    private $system;
    private $debug;

    public function __construct ($system, $debug = 2) 
    {
        $this->system = $system;
        $this->debug = $debug;
        define("NO_ERROR", 2);

        if($this->debug === true) {
            error_reporting(0);

            set_error_handler(function ($level, $message, $file, $line, $context) {
                $this->handler(false, $level, $message, $file, $line, $context);
            }, E_ALL);
    
            register_shutdown_function(function () {
                $this->handler(true);
            });
        } else if($debug === 2) {
            error_reporting(-1);
            ini_set('error_reporting', -1);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        } else {
            error_reporting(0);
        }
    }

    private function handler ($fatal = false, $level = null, $message = null, $file = null, $line = null, $context = null)
    {
        if($fatal) {
            $last_error = error_get_last();

            if($last_error and !empty($last_error))  {
                $level = $last_error["type"];
                $message = $last_error["message"];
                $file = $last_error["file"];
                $line = $last_error["line"];
            } else $fatal = NO_ERROR;
        }

        if($this->system->hasCallbacks("error")) $this->system->invokeCallback("error", [ "fatal" => $fatal, "level" => $level, "message" => $message, "file" => $file, "line" => $line ]);
        else {
            if($fatal !== NO_ERROR) {
                require (APPS . "/core/html/error.php"); 
                if($fatal) die;
            }
        }
    }
}