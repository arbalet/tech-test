<?php

namespace Skybet\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionToken implements Token
{
	const TOKEN_NAME = 'token';

	public $session;

	public function __construct()
	{
		$this->session = New Session;
	}

	public function getNewToken()
	{
		$token = bin2hex(openssl_random_pseudo_bytes(10));
		$this->session->set(self::TOKEN_NAME,$token);
		return $token;
	}

	public function getCurrentToken()
	{
		return $this->session->get(self::TOKEN_NAME);
	}
}