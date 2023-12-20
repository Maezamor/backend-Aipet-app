<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check there token or not
        $token =  $request->header('Authorization');
        //inisalisasi autorize
        $authenticate = true;

        if(!$token){
            $authenticate = false;
        }

        // validate tthere token in database
        $user = User::where('token', $token)->first();

        if(!$user){
            $authenticate = false;
        }else{
            // next login
           
            Auth::login($user);

        }

        //checking valid or not
        if ($authenticate) {
            return $next($request);
        }else{
               // memberikan feedback kalu tidak terauthentukasi
               return response()->json([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                 ]
            ])->setStatusCode(401);
        }

        
    }
}
