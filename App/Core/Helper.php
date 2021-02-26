<?php

namespace Cartrack\Core;

use Slim\Routing\RouteContext;
use Cartrack\Model\RefreshToken;
use Cartrack\Model\User;
use Firebase\JWT\JWT;

class Helper
{
	/**
	 * Convert datetime with seconds to readable format
	 * @param $datetime with milliseconds
	 * @param $format datetime format
	 * @param $bool (bool)
	 * @return datetime
	 */
	public static function datetime_ms($datetime = null, $format = 'M d, Y h:i:s.v A', $bool = false) {

        if (!empty($datetime)) {

            $dt = new DateTime($datetime);

            $date = $dt->format($format);

        } else {

            $date = date('Y-m-d H:i:').sprintf('%03.3f', date('s')+fmod(microtime(true), 1));
            
            if ($bool) {

                $dt = new DateTime($date);

                $date = $dt->format($format);
            }
        }
        
        return $date;
    }

    /**
     * Response
     * @param int $status header status
     * @param object $payload
     * @param object $response
     * @return json
     */
    public static function jsonResponse(int $status, $payload, $response)
    {   
        $json = json_encode($payload);

        $response->getBody()->write($json);
        return $response
                  ->withHeader('Content-Type', 'application/json')
                  ->withStatus($status);
    }

    public static function refreshToken($request)
    {
        $routeContext = RouteContext::fromRequest($request);
        
        $requestUrl = $routeContext->getRoute();

        $activeUser = $request->getHeader('ActiveUser');

        $params = $request->getServerParams();

        $user = new User();

        $refresh = new RefreshToken();

        $where = [
            'id' => current($activeUser), 
        ];

        $result = $user->fetch($where);

        if (!empty($result)) {
            $issued = time();
            $expired = $issued + (60 * 60); // valid for 1 hour
            $iss = $params['HTTP_HOST'].$requestUrl->getPattern();

            $auth = [
                "iss" => $iss,
                "iat" => $issued,
                "exp" => $expired,
                'data' => $result,
            ];

            $token = JWT::encode($auth, $params['JWT_KEY']);

            $save = [
                'uid'   => current($activeUser),
                'hash'  => $token
            ];

            $refreshToken = $refresh->fetch(['uid' => current($activeUser)]);
            
            if (empty($refreshToken)) {
                $refresh->insert($save);

                return $token;
            }

            return false;
        }
    }
}
?>