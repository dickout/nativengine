<?php

namespace apps\core\classes;

use apps\core\classes\Json;
use apps\core\classes\FileManager;

class Config
{
    public $name;
    public $fullpath;
    public $template = [];
    public $config = [];

    public function __construct ($path, $name, $template = [])
    {
        $this->name = $name;
        $this->fullpath = FileManager::getFullPath($path, $name, "json");
        $this->template = $template;
        FileManager::createFile($this->fullpath);

        if(!trim(FileManager::getContent($this->fullpath)))
            FileManager::putContent($this->fullpath, Json::encode($this->template));

        $this->config = Json::decode(FileManager::getContent($this->fullpath));
    }

    public function getOption ($name) {
        if(isset($this->config[$name]))
            return $this->config[$name];
    }

    public function setOption ($name, $value) {
        if(isset($this->config[$name]))
            return $this->config[$name] = $value;
    }

    public function save ($recreate = false) {
        if($recreate) 
            $this->config = $this->template;

        FileManager::putContent($this->fullpath, Json::encode($this->config));
    }
}