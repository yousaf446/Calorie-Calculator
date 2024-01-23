<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $user_service;
    public function __construct(Request $request, UserService $user_service)
    {
        parent::__construct($request);
        $this->user_service = $user_service;
    }

    /**
     * Finds all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->user_service->findAll();
        return response()->json($data);
    }

    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // validate incoming request
        $status_code = Response::HTTP_OK;
        $request_data = $this->_request->all();
        $response_data = [];

        $validator = Validator::make($request_data, [
            'name' => 'required|max:200',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:10|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/|confirmed',
            'role' => 'required|max:50|in:admin,subscriber',
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $user = $this->user_service->store($request_data);

            if ($user) {
                $response_data['message'] = 'User created successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }
        return response()->json($response_data, $status_code);
    }

    /**
     * Get user by id.
     *
     * @param  uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status_code = Response::HTTP_OK;
        $response_data = [];
        $user = $this->_request['user'];

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'User not found!';
            $status_code = Response::HTTP_NOT_FOUND;
        } else {
            $user_id = $user['id'];

            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN'] || $id == $user_id) {
                $response_data = $this->user_service->findById($id);
            } else  {
                $response_data['message'] = 'User not found!';
                $status_code = Response::HTTP_NOT_FOUND;
            }
        }
        
        return response()->json($response_data, $status_code);
    }

    /**
     * Update user by id.
     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $status_code = Response::HTTP_OK;
        $request_data = $this->_request->all();
        $response_data = [];
        $user = $this->_request['user'];
        
        $validator = Validator::make($request_data, [
            'id' => 'required|exists:users',
            'name' => 'required|max:200',
            'role' => 'required|max:50|in:admin,subscriber'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $id = $request_data['id'];
            unset($request_data['id']);
            
            $user_id = $user['id'];
            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN'] || $id == $user_id) {
                $user = $this->user_service->update($id, $request_data);
            } else  {
                $user = false;
            }

            if ($user) {
                $response_data['message'] = 'User updated successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }
        return response()->json($response_data, $status_code);
    }

    /**
     * Delete user by id.
     *
     * @param  uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status_code = Response::HTTP_OK;
        $response_data = [];

        $user = $this->_request['user'];
        
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users',
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $user_id = $user['id'];
            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN'] || $id == $user_id) {
                $user = $this->user_service->delete($id);
            } else {
                $user = false;
            }

            if ($user) {
                $response_data['message'] = 'User deleted successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }

        return response()->json($response_data, $status_code);
    }
}
