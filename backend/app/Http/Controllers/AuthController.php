<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Login user with token
     */
    public function login()
    {
        $status_code = Response::HTTP_OK;
        $request_data = $this->_request->all();
        $response_data = [];

        $validator = Validator::make($request_data, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $token = auth()->attempt($request_data);

            if ($token) {
                $response_data = [
                    'access_token' => $token,
                    'token_type'   => 'bearer',
                    'expires_in'   => auth()->factory()->getTTL()
                ];
            } else {
                $status_code = Response::HTTP_NOT_FOUND;
                $response_data['message'] = 'Email or Password mismatch!';
            }
        }

        return response()->json($response_data, $status_code);
    }
}
