<?php

namespace Skybet\File;

use Skybet\Config\Config;

class File implements FileInterface
{

	private $fileName;

	private $data;

	/**
	*
	*	Load information form file
	*	@return NULL
	*
	**/

	public function __construct()
	{
		$config = Config::Instance(); // make singleton instanse of Config class
		$this->fileName = $config->getFilePath()."/".$config->getFileName();
	}

	/**
	*
	*	Load all info from file
	*	@return json
	*
	**/

	public function Load()
	{
		
		// check if file exists and if not create a empty one
		if (!$this->FileExists($this->fileName))
		{
			$this->CreateFile($this->fileName);
		} 

		// read all information for file
		$data = $this->ReadFile();

		return json_decode($data, true);
	}

	/**
	*
	*	Save record into file
	*	@param $firstname string
	*	@param $surname string
	*	@return NULL
	*
	**/

	public function Save($firstname,$surname)
	{
		// read all information for file
		$data = $this->ReadFile();
		$data = json_decode($data,true);

		//prepare data for saving.
		$z = is_array($data) ? (max(array_keys($data))+1) : 0;

		$data[$z]['firstname'] = $firstname;
		$data[$z]['surname'] = $surname;

		$this->data = $data;

		// save data to file
		$this->commit();
	}

	/**
	*
	*	Delete record from file
	*	@param $id integer
	*	@return NULL
	*
	**/

	public function Delete($id)
	{

		if ($id)
		{
			$data = $this->ReadFile();
			$data = json_decode($data,true);

			unset($data[$id]); // remove record from array by id
			
			// save data to file
			$this->commit();
		} else {
			throw new Exception("Error: Empty value", 1);			
		}
	}

	/**
	*
	*	Update record in file
	*	@param $firstname string
	*	@param $surname string
	*	@param $id integer
	*	@return NULL
	*
	**/

	public function Update($firstname,$surname,$id)
	{
		$data = $this->ReadFile();
		$data = json_decode($data,true);

		//let's update the data by id
		$data[$id]['firstname'] = $firstname;
		$data[$id]['surname'] = $surname;
		
		// save data to file
		$this->commit();
	}


	/**
	*
	*	Check if file exists
	* 	@param $file string
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
	* 	@param $file string
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

	private function Commit()
	{
		$data = $this->data;

		$f = @fopen($this->fileName,'w');
		@fputs($f,json_encode($data));
		@fclose();
	}
}