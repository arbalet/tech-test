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
	 * @var Csrf
	 */
	private $token;

	public function __construct(Response $response, Request $request, File $file, SessionToken $token)
	{
		$this->response = $response;
		$this->request = $request;
		$this->file = $file;
		$this->token = $token;
	}

	public function ListAll()
	{
				
		$data['persons'] = $this->file->load();

		$data['token'] = $this->token->getNewToken();

		//\Vardump::singleton()->dump($data);

		$template = View::make('../../templates/list',$data);

		return $this->response->setContent($template->render());
	}

	public function add(Request $request)
	{
		//\Vardump::singleton()->dump($request->get('people'));

		$this->checkToken($request);

		$data = $request->get("people");
		$firstname = $data[0]['firstname'];
		$surname = $data[1]['surname'];

		if (!$this->validateEmpty($firstname) && !$this->validateEmpty($surname))
		{
			throw new \Exception("Fields cannot be empty", 1);			
		}

		if (!$this->validateRegex($firstname) || !$this->validateRegex($surname))
		{
			throw new \Exception("Name can be only letters", 1);			
		}		
		
		try {
			$this->file->save($firstname,$surname);
		} catch (Exception $e)
		{
			echo "Cannot save to file";
		}

		return header("Location: /");
	}

	public function del(Request $request)
	{

		if ($request->getMethod() == "POST")
		{
			$this->checkToken($request);

			$id = $request->get("id");

			try {
					$this->file->delete($id);
					$res['res'] = 1;
			} catch (Exception $e)
			{
				$res['res'] = "Cannot delete record";
			}
		} else {
			$res['res'] = "Access denied!";
		}

		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		$response->setContent(json_encode($res));

		return $response->send();		
	}

	public function update(Request $request)
	{

		if ($request->getMethod() == "POST")
		{
			$this->checkToken($request);

			$id = $request->get("id");
			$firstname = $request->get("firstname");
			$surname = $request->get("surname");

			if (is_numeric($id) && ($this->validateEmpty($firstname) || $this->validateEmpty($surname)))
			{
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


	private function validateEmpty($value)
	{
		return !empty($value) ? true : false;
	}

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