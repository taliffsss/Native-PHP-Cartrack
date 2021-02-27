<?php

namespace Cartrack\Controller;

use Slim\Routing\RouteContext;
use Cartrack\Core\Helper;
use Cartrack\Model\Crud as crudModel;
use Firebase\JWT\JWT;

class Crud {

	protected $user;

	public static function show($request, $response)
	{
		$object = (object) [
			'code' 		=> 400,
			'message' 	=> 'Bad request',
			'payload'	=> null
		];

		$user = new crudModel();

		$results = $user->fetchall();

		$object->message = 'Empty data';

		if (!empty($results)) {
			$object->message = 'Successfully fetch';
			$object->code = 200;
			$object->payload = $results;
		}

		return Helper::jsonResponse($object->code, $object, $response);
	}

	public static function store($request, $response)
	{

		$object = (object) [
			'code' 		=> 400,
			'message' 	=> 'Bad request',
			'payload'	=> null,
			'token'		=> null,
		];

		$routeContext = RouteContext::fromRequest($request);
        $requestUrl = $routeContext->getRoute();

		$decode = json_decode(file_get_contents('php://input'));

		$params = $request->getServerParams();

		if (!empty($decode)) {

			$user = new crudModel();

			$save = [
				'item'		=> $decode->item,
				'i_desc' 	=> $decode->desc,
				'qty' 		=> $decode->qty,
			];

			$result = $user->insert($save);

			$skey = $params['JWT_KEY'];

			$issued = time();
			$expired = $issued + (60 * 60); // valid for 1 hour
			$iss = $params['HTTP_HOST'].$requestUrl->getPattern();

			$auth = [
				"iss" => $iss,
			    "iat" => $issued,
		        "exp" => $expired,
			    'data' => $result,
			];

			if (!empty($result)) {
				$object->token = JWT::encode($auth, $skey);
				$object->message = 'Data has been created';
				$object->payload = $result;
				$object->code = 200;
			}

			return Helper::jsonResponse($object->code, $object, $response);
		}
	}

	public static function remove($request, $response)
	{
		$object = (object) [
			'code' 		=> 400,
			'message' 	=> 'Bad request'
		];

		$decode = json_decode(file_get_contents('php://input'));

		if (!empty($decode)) {

			$user = new crudModel();

			$where = [
				['id', '=', $decode->id]
			];

			$del = $user->_delete($where, false);

			if ($del) {
				$object->message = 'Data has been deleted';
				$object->code = 200;
			}

			return Helper::jsonResponse($object->code, $object, $response);
		}
	}

	public static function update($request, $response, $arg)
	{
		$object = (object) [
			'code' 		=> 400,
			'message' 	=> 'Bad request'
		];

		$decode = json_decode(file_get_contents('php://input'));

		$user = new crudModel();

		$update = [
			'item'		=> $decode->item,
			'i_desc' 	=> $decode->desc,
			'qty' 		=> $decode->qty,
		];

		$patch = $user->update($update, $arg);

		if ($patch) {
			$object->token = Helper::refreshToken($request);
			$object->message = 'Successfully update';
			$object->code = 200;
		}

		return Helper::jsonResponse($object->code, $object, $response);
	}
}

?>