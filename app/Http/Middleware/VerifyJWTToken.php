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
        if (isset($_SERVER['HTTP_TOKEN']))
        {
            $token = $_SERVER['HTTP_TOKEN'];
        } 
        else
        {
            $token = $request->input('token');
        }

        try
        {
            JWTAuth::setToken($token);
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
            {
                return response()->json(["error" => 'token expired']);
            }
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
            {
                return response()->json(["error" => 'token invalid']);
            }
            else
            {
                return response()->json(["error" => 'token riquered']);
            }
        }
        return $next($request);
    }
}