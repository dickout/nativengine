<?php

namespace apps\testapp\classes;

use apps\router\classes\WebApp;
use apps\router\classes\Router;

class App extends WebApp
{
    public function run () {
        Router::addApplication(
            "testapp", 
            [ "webdir" => "", "frontdir" => "/assets" ],
            [ "$^" => ["controller" => "Main", "action" => "main"] ]
        );
        
    }
}

?>