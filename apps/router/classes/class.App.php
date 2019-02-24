<?php

namespace apps\router\classes;

use apps\core\classes\Autoloader;
use apps\router\classes\Router;
use apps\core\classes\App as NativeApp;

class App extends NativeApp
{
	public $relations = ["core"];
	public $controllerLoader;
	public $modelLoader;

	public function run () {

		Router::factory();

		$this->controllerLoader = Autoloader::register("router", "controller.");
		$this->modelLoader = Autoloader::register("router", "model.");

		$this->system->createCallback("ready", function () {
			Router::dispatch();
		});
	}
}

?>