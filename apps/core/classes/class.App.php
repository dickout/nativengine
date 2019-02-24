<?php

namespace apps\core\classes;

class App 
{
	public $system;

	public function __construct ($system) {
		$this->system = $system;
	}

	public function run () {
		
	}

	public function dispatch () 
	{
		$queue = $this->system->getAppLoader()->getApps();
		
		do {
			foreach ($queue as $appname => $app) {
				$relations = $this->system->getAppLoader()->$appname()->relations;

				if(isset($relations)) {
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