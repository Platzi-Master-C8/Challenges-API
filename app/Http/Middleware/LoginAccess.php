<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        $user = User::where('sub', '=', $request->header('user'))->first();
        $request->headers->set('user', $user->id);
        return $next($request);
    }
}
