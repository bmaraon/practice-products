<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
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

        $user = User::create($input);

        return $this->sendResponse([
            'token' => $user->createToken('userlution-web-portal')->plainTextToken,
        ], 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            $user             = Auth::user();
            $success['token'] = $user->createToken('userlution-web-portal')->plainTextToken;
            $success['name']  = $user->name;

            return $this->sendResponse([
                'token' => $user->createToken('userlution-web-portal')->plainTextToken,
            ], 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
