<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateBearerToken
{
    /**
     * Handle the incoming requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        // To do: need to have a refresh token process

        try {
            $nonExpiredTokens = null;

            // get user
            if ($user = Auth::user()) {

                // retrieve the user's tokens that are not yet expired
                $nonExpiredTokens = $user->tokens()
                    ->where('token', $request->bearerToken())
                    ->where('expires_at', '>', Carbon::now())
                    ->first();
            }

            if (!$nonExpiredTokens) {
                return response()->json(['message' => 'Unauthorised'], 401);
            }
        } catch (\Exception$e) {
            return response()->json(['message' => 'Unauthorised'], 401);
        }

        return $next($request);
    }
}
