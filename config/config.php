<?php

namespace Skybet\Config;

class Config
{
	private static $path = "/../storage";

	private static $file = "users.json";

	private static $instance;

	/**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Config();
        }
        return $inst;
    }

    protected function __construct()
    {

    }

    protected function __clone()
    {
        //Me not like clones! Me smash clones!
    }

    private function __wakeup()
    {
    }

	public function getFilePath()
	{
		return self::getDir()."/".self::$path;
	}

	public function getFileName()
	{
		return self::$file;
	}

	private static function getDir()
	{
		return __DIR__;
	}
}