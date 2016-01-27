<?php

namespace Skybet\Controllers; 

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Journey\View;
use Skybet\File\File;
use Skybet\Token\SessionToken;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController {

	/**
	 * @var Response
	 */
	private $response;
	/**
	 * @var Request
	 */
	private $request;
		
	/**
	 * @var Token
	 */
	private $token;

	public function __construct(Response $response, Request $request, File $file, SessionToken $token)
	{
		$this->response = $response;
		$this->request = $request;
		$this->file = $file;
		$this->token = $token;
	}


	/**
	*
	*	Load all records from file
	*	@return Template list.php
	*
	*/

	public function ListAll()
	{
				
		$data['persons'] = $this->file->load();

		$data['token'] = $this->token->getNewToken();

		$template = View::make('../../templates/list',$data);

		return $this->response->setContent($template->render());
	}

	/**
	*
	*	Add record to file
	*
	*/

	public function add(Request $request)
	{
		
		$this->checkToken($request); // check if tokem match

		$data = $request->get("people");
		$firstname = $data[0]['firstname'];
		$surname = $data[1]['surname'];

		/*
		*	Here we validate if firstname and surname are empty. at least one value need to be filled
		*/
		if (!$this->validateEmpty($firstname) && !$this->validateEmpty($surname))
		{
			throw new \Exception("Fields cannot be empty", 1);			
		}

		/*
		*	Here we validate if firstname and surname are not alphabetic. Names cannot contains numbers or special characters
		*/

		if (!$this->validateRegex($firstname) || !$this->validateRegex($surname))
		{
			throw new \Exception("Name can be only letters", 1);			
		}		
		
		//let try to save info into the file

		try {
			$this->file->save($firstname,$surname);
		} catch (Exception $e)
		{
			echo "Cannot save to file";
		}

		return header("Location: /");
	}


	/**
	*
	*	Remove record from file
	*
	*/

	public function del(Request $request)
	{

		// check if method is POST. This is done by route too.
		if ($request->getMethod() == "POST")
		{
			$this->checkToken($request); // check if tokem match

			$id = $request->get("id");

			// try delete record from file
			try {
					$this->file->delete($id);
					$res['res'] = 1;
			} catch (Exception $e)
			{
				$res['res'] = "Cannot delete record";
			}
		} else {
			$res['res'] = "Access denied!"; // if method is not post return this message
		}

		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		$response->setContent(json_encode($res));

		return $response->send();		
	}

	/**
	*
	*	Update record in file
	*
	*/

	public function update(Request $request)
	{
		// check if method is POST. This is done by route too.
		if ($request->getMethod() == "POST")
		{
			$this->checkToken($request); // check if tokem match

			$id = $request->get("id");
			$firstname = $request->get("firstname");
			$surname = $request->get("surname");

			// we check if id is numeric and if fields are not empty. at least one need to be filled

			if (is_numeric($id) && ($this->validateEmpty($firstname) || $this->validateEmpty($surname)))
			{	
				/*
				*	Here we validate if firstname and surname are not alphabetic. Names cannot contains numbers or special characters
				*/
				if (!$this->validateRegex($firstname) || !$this->validateRegex($surname))
				{
					$res['res'] = "Only letters";
				} else {
					try {
							$this->file->update($firstname,$surname,$id);
							$res['res'] = 1;
					} catch (Exception $e)
					{
						$res['res'] = "Cannot update record into file";
					}
				} 
			} else {
				$res['res'] = "Empty values";
				
			}
		} else {
			$res['res'] = "Access denied!";
		}
		
		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		$response->setContent(json_encode($res));

		return $response->send();		
	}


	/**
	*
	*	Check if variable is empty
	*	@param $value string
	*	@return boolean
	*
	*/

	private function validateEmpty($value)
	{
		return !empty($value) ? true : false;
	}

	/**
	*
	*	Check if variable is only leters
	*	@param $value string
	*	@return boolean
	*
	*/

	private function validateRegex($value)
	{
		preg_match("/[^a-zA-Z]/",$value,$match);

		if (empty($match))
		{
			return true;
		} else {
			return false;
		}
	}

	/**
	*
	*	Check if token from form and from session match
	*	@param $value object
	*	@return NULL
	*
	*/

	public function checkToken($request)
	{
		$currentToken = $this->token->getCurrentToken();
		$postedToken = $request->get("token");

		if ($currentToken != $postedToken)
		{
			throw new \Exception("Token mismatch", 1);			
		} 
	}
}