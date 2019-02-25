<?php

namespace apps\core\classes;

use apps\core\classes\FileManager;
use apps\core\classes\ErrorHandler;

class App 
{
	public $system;
	public $config;
	public $dirs = [
		ROOT . "/storage/logs",
		ROOT . "/bin"
	];

	public function __construct ($system) 
	{
		$this->system = $system;
	}

	public function run () {
		
	}

	public function factory ()
	{
		$this->config = new Config(APPS . "/core", "config", [ "debug" => "native" ]);
		foreach($this->dirs as $dir)
			FileManager::createDir($dir);
	}

	public function dispatch () 
	{
		$this->factory();

		$debug = $this->config->getOption("debug");
		if($debug == "native") $debug = true;
		else if($debug == "none") $debug = false;
		else if($debug == "php") $debug = 2;
		else $debug = 2;
		
		$queue = $this->system->getAppLoader()->getApps();
		$errorHandler = new ErrorHandler($this->system, $debug);
		
		do {
			foreach ($queue as $appname => $app) {
				if(isset($this->system->getAppLoader()->$appname()->relations)) {
					$relations = $this->system->getAppLoader()->$appname()->relations;
					$relationsLoaded = true;

					foreach($relations as $name)
						if(isset($queue[$name])) $relationsLoaded = false;

					if(!$relationsLoaded) continue;
				}
	
				$this->system->getAppLoader()->$appname()->run();
				unset($queue[$appname]);
			}
		} while (!empty($queue));



		$this->system->invokeCallback("ready");
	}
}

?>