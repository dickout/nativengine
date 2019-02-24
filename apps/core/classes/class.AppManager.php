<?php

namespace apps\core\classes;

use apps\core\classes\FileManager;

class AppManager 
{
	private $apps = [];

	public function __construct($system) {
		$dirs = FileManager::getDirList(APPS);

		foreach ($dirs as $key => $value) {
			$path = APPS . "/" . $value;
			$class = "\\apps\\".$value."\\classes\\App";

			if(FileManager::fileExists($path . "/classes/class.App.php") and class_exists($class))
				$this->apps[$value] = new $class($system);
		}
	}

	public function getApps (): array
	{
		return $this->apps;
	}

	public function __call($appname, $arguments) {
		return $this->apps[$appname];
	}
}