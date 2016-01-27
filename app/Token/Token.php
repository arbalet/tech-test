<?php

namespace Skybet\Token;


interface Token
{

	public function getNewToken();

	public function getCurrentToken();

}