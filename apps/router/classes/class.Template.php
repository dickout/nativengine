<?php

namespace apps\router\classes;

use apps\core\classes\FileManager;
use apps\router\classes\SpecialCode;

class Template 
{
	public $exists = true;
	public $vars = [];
	public $currentTemplate = "";

	public function __construct($viewer, $template, $route)
	{
		if(!is_file($viewer)) 
			$this->exists = false;
		else {
			$this->viewer = $viewer;
			$this->route = $route;
			
			$this->vars["title"] = "Application Title";
			$this->vars["view"] = $this->route["path"]["action"];
			$this->vars["front"] = DOMAIN . "/apps/" . $this->route["app"] . $this->route["data"]["frontdir"];
		}
	}

	public function render ($vars = []) 
	{
		if($this->exists) 
		{
			$this->vars += $vars;
			echo $this->parse();
		}
	}

	public function parse (): string
	{
		$template = FileManager::getContent($this->viewer);
		$content = $this->parseTemplate($template);

		return $content;
	}

	public function parseVars ($template)
	{
		preg_match_all("/{{.*}}/i", $template, $matches);
		$matches = $matches[0];

		foreach ($matches as $string) {
			$var = str_replace(["{{", "}}"], "", $string);
			if(isset($this->vars[$var]))
				$template = str_replace($string, $this->vars[$var], $template);
		}

		return $template;
	}

	public function parseTemplate ($template)
	{
		$this->currentTemplate = $template;
		$template = $this->parseVars($template);

		foreach (SpecialCode::getFunctions("template", $template) as $string) {
			$data = SpecialCode::parseArguments("template", $string);

			$templateName = isset($data["path"]) ? $data["path"] . "/tpl." . $data["name"] : "tpl." . $data["name"];

			/**
			 * TODO Execute Php Code in <?php ?>
			 */
			
			ob_start();
			include(APPS . "/" . $this->route["app"] . $this->route["data"]["webdir"] . "/templates/" . $templateName . ".php");
			$output = ob_get_contents();
			ob_end_clean();

			$templateContent = $this->parseTemplate($output);

			$template = str_replace($string, $templateContent, $template);
		}

		return $template;
	}
}