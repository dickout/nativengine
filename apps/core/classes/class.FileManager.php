<?php

namespace apps\core\classes;

class FileManager 
{
	public static function getFullPath (string $path, string $name = "", string $ext = ""): string 
	{
		if($name and !$ext) return $path . "/" . $name;
		else if($name and $ext) return $path . "/" . $name . "." . $ext;
		else return $path;
	}

	public static function createFileThread (string $fullPath, string $option = "w", callable $callable = null)
	{
		$fileThread = fopen($fullPath, $option);

		if($callable) 
			$callable($fileThread);

		fclose($fileThread);
	}

	public static function returnFile (string $fullPath)
	{
		if(self::fileExists($fullPath))
			return require ($fullPath);
		return null;
	}

	public static function createFile (string $fullPath, $recreate = false)
	{
		if(!$recreate and !self::fileExists($fullPath))
			self::createFileThread($fullPath);
		else if($recreate)
		{
			if(self::fileExists($fullPath))
				self::deleteFile($fullpath);
			self::createFileThread($fullPath);
		}

	}

	public static function createDir (string $fullPath, $recreate = false)
	{
		if(!self::dirExists($fullPath)) {
			if(!self::dirExists(dirname($fullPath)))
				self::createDir(dirname($fullPath));

			return mkdir($fullPath);
		}
	}

	public static function deleteFile (string $fullPath)
	{
		if(self::fileExists($fullPath)) 
			unlink($fullPath);
	}

	public static function fileExists (string $fullPath): bool
	{
		return is_file($fullPath);
	}

	public static function dirExists (string $fullPath): bool
	{
		return is_dir($fullPath);
	}

	public static function getDirList (string $fullpath): array
	{
		if(self::dirExists($fullpath))
		{
			$dirs = scandir($fullpath);
			for($i = 0; $i < 2; $i++) 
				array_shift($dirs);

			foreach ($dirs as $key => $name) {
				if(!self::dirExists($fullpath . "/" . $name))
					unset($dirs[$key]);
			}

			return $dirs;
		}
	}

	public static function getFileList (string $fullpath): array
	{
		if(self::dirExists($fullpath))
		{
			$dirs = scandir($fullpath);
			for($i = 0; $i < 2; $i++) 
				array_shift($dirs);

			foreach ($dirs as $key => $name) {
				if(!self::fileExists($fullpath . "/" . $name))
					unset($dirs[$key]);
			}

			return $dirs;
		}
	}

	public static function putContent (string $fullPath, string $content) 
	{
		if(self::FileExists($fullPath))
			file_put_contents($fullPath, $content);
	}

	public static function getContent (string $fullPath): string
	{
		if(self::fileExists($fullPath))
			return file_get_contents($fullPath);
		return "";
	}

	public static function isEmpty (string $fullPath): bool
	{
		return trim(self::getContent($fullPath)) ? false : true;
	}
}