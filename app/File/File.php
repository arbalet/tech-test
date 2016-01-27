<?php

namespace Skybet\File;

use Skybet\Config\Config;

class File implements FileInterface
{

	private $fileName;

	/**
	*
	*	Load information form file
	*	@return NULL
	*
	**/

	public function __construct()
	{
		$config = Config::Instance();
		$this->fileName = $config->getFilePath()."/".$config->getFileName();
	}

	public function Load()
	{
		
		if (!$this->FileExists($this->fileName))
		{
			$this->CreateFile($this->fileName);
		} 

		$data = $this->ReadFile();

		return json_decode($data, true);
	}

	public function Save($firstname,$surname)
	{
		
		$data = $this->ReadFile();
		$data = json_decode($data,true);

		//prepare data for saving.
		$z = is_array($data) ? (max(array_keys($data))+1) : 0;

		$data[$z]['firstname'] = $firstname;
		$data[$z]['surname'] = $surname;

		$f = @fopen($this->fileName,'w');
		@fputs($f,json_encode($data));
		@fclose();
	}

	public function Delete($id)
	{

		if ($id)
		{
			$data = $this->ReadFile();
			$data = json_decode($data,true);

			unset($data[$id]);
			
			$f = @fopen($this->fileName,'w');
			@fputs($f,json_encode($data));
			@fclose();
		} else {
			throw new Exception("Error: Empty value", 1);
			
		}
	}

	public function Update($firstname,$surname,$id)
	{
		$data = $this->ReadFile();
		$data = json_decode($data,true);

		$data[$id]['firstname'] = $firstname;
		$data[$id]['surname'] = $surname;
		
		$f = @fopen($this->fileName,'w');
		@fputs($f,json_encode($data));
		@fclose();
	}


	/**
	*
	*	Check if file exists
	*	@return boolean
	*
	**/

	private function FileExists($file)
	{
		return !file_exists($file) ? false : true;
	}

	/**
	*
	*	Create file
	*	@return NULL
	*
	**/

	private function CreateFile($file)
	{
		try {
			$f = fopen($file,"w");
			fwrite($f, "");
			@fclose();
		} catch (Exception $e)
		{
			echo "Cannot create file: ".$e->getMessage();
		}
	}

	/**
	*
	*	Read file content
	*	@return $content
	*
	**/

	private function ReadFile()
	{
		$file = $this->fileName;

		$content = file_get_contents($file);

		return $content;
	}
}