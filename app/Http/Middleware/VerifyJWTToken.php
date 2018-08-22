<?php
namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyJWTToken
{
    public function __construct()
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        echo __LINE__; die();

        if (isset($_SERVER['HTTP_TOKEN'])) {
            $token = $_SERVER['HTTP_TOKEN'];
        } else {
            $token = $request->input('token');
        }

        try {
            $user = JWTAuth::toUser($token);
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json($this->response->toString(false, $this->messages['token']['expired']));
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json($this->response->toString(false, $this->messages['token']['invalid']));
            } else {
                return response()->json($this->response->toString(false, $this->messages['token']['riquered']));
            }
        }
        return $next($request);
    }
}