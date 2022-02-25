<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginAccess
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->header('user') == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $request->header('user'),
        ])->get(env('AUTH0_API_LOGIN'));


        $user = json_decode($response);

        $request->headers->set('user', $user->id);
        return $next($request);
    }
}
