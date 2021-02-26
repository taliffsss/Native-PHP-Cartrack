<?php
namespace Cartrack\Libraries;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\ExpiredException;
use Cartrack\Core\Helper;
use Cartrack\Model\User;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class AuthorizationMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $payload = (object) [
            'code'      => 400,
            'message'   => 'Invalid token'
        ];

        $error = true;

        try {

            $user = new User;

            $auth = $request->getHeader('Authorization');

            if (!empty($auth)) {

                $token = explode(' ', current($auth));

                $params = $request->getServerParams();

                // decode Token
                $decode = JWT::decode(end($token), $params['JWT_KEY'], ['HS256']);

                // check if token is valid
                if (!empty($decode) && !empty($decode->data)) {

                    $error = false;

                    $where = [
                        'id'        => $decode->data->id,
                        'name'      => $decode->data->name,
                        'username'  => $decode->data->username, 
                    ];

                    $result = $user->fetch($where);

                    if (!empty($result)) {
                        $response = $handler->handle($request);

                        $response->withHeader('Content-type', 'application/json');
                        
                        return $response;
                    } else {
                        $error = true;
                    }
                }
            }

            if ($error) {
                return Helper::jsonResponse($payload->code, $json, new Response());
            }
            
        } catch (\Firebase\JWT\ExpiredException $e) {

            $newToken = Helper::refreshToken($request);

            if (!$newToken) {
                $payload->message = 'Expired Token';

                return Helper::jsonResponse($payload->code, $payload, new Response());
            }

            $response = $handler->handle($request);

            $current = (string) $response->getBody();

            $json = json_encode($current);

            $json->token = $newToken;

            return Helper::jsonResponse($payload->code, $json, new Response());
        }

        
    }
}
