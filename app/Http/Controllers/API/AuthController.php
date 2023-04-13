<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     *
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input               = $request->all();
        $input['created_by'] = Auth::id() ?? null;
        $input['password']   = bcrypt($input['password']);
        $user                = User::create($input);

        // set the expiration date for the token to 7 days from now
        $expirationDate = Carbon::now()->addDays(7)->toDateTime();

        // user the actual token rather than the plainTextToken
        $token = $user->createToken('login-user', ['*'], $expirationDate)->accessToken->token;

        // response
        return $this->sendResponse(['token' => $token], 'User login successfully.');
    }

    /**
     * Login api
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     *
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            $user = Auth::user();

            // set the expiration date for the token to 7 days from now
            $expirationDate = Carbon::now()->addDays(7)->toDateTime();

            // user the actual token rather than the plainTextToken
            $token = $user->createToken('login-user', ['*'], $expirationDate)->accessToken->token;

            // response
            return $this->sendResponse(['token' => $token], 'User login successfully.');

        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     *
     */
    public function logout(Request $request): JsonResponse
    {
        // get user
        $user = Auth::user();

        // retrieve the user's tokens that are not yet expired
        $user->tokens()
            ->where('token', $request->bearerToken())
            ->delete();

        // for request sessions
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->sendResponse(null, 'User logged out successfully.');
    }
}
