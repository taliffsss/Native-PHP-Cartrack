<?php

namespace Cartrack\Controller;

use Slim\Routing\RouteContext;
use Cartrack\Core\Helper;
use Cartrack\Model\User;
use Firebase\JWT\JWT;

class Users {

	protected $user;

	public static function show($request, $response)
	{
		$object = (object) [
			'code' 		=> 400,
			'message' 	=> 'Bad request',
			'payload'	=> null
		];

		$user = new User;

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

		$decode = json_decode(file_get_contents('php://input'), true);

		$params = $request->getServerParams();

		if (!empty($decode)) {

			$user = new User;

			$decode['password'] = Users::password_hash($decode['password']);

			$result = $user->insert($decode);

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

		$decode = json_decode(file_get_contents('php://input'), true);

		if (!empty($decode)) {

			$user = new User();

			$where = [
				['id', '=', $decode['id']]
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

		$decode = json_decode(file_get_contents('php://input'), true);

		$user = new User();

		if (array_key_exists('password', $decode)) {
			$decode['password'] = Users::password_hash($decode['password']);
		}

		$patch = $user->update($decode, $arg);

		if ($patch) {
			$object->token = Helper::refreshToken($request);
			$object->message = 'Data has been deleted';
			$object->code = 200;
		}

		return Helper::jsonResponse($object->code, $object, $response);
	}

	/**
	 * Password hashing
	 * @link https://www.php.net/manual/en/function.password-hash.php
	 * @param string $password
	 * @return bool
	 */
	private static function password_hash($password) {

		$options = ['cost' => 12];

		$hash = password_hash($password, PASSWORD_BCRYPT, $options);

		return $hash;

	}

	/**
	 * Password Verify
	 * @link https://www.php.net/manual/en/function.password-verify.php
	 * @param string $password
	 * @param string $hash
	 * @return bool
	 */
	private static function _assword_verify($password, $hash) {

		$verified = password_verify($password, $hash);

		return $verified;

	}
}

?>