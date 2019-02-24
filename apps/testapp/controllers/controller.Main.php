<?php

namespace apps\testapp\controllers;

use apps\router\classes\Controller;

class Main extends Controller
{
    public static function main () 
    {
        self::setVars(["test" => "123"]);
    }
}