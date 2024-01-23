<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UserSettingsService;
use Symfony\Component\HttpFoundation\Response;

class UserSettingsController extends Controller
{
    protected $user_settings_service;
    public function __construct(Request $request, UserSettingsService $user_settings_service)
    {
        parent::__construct($request);
        $this->user_settings_service = $user_settings_service;
    }

    /**
     * Get user settings by user id.
     *
     * @param  uuid  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $status_code = Response::HTTP_OK;
        $response_data = [];
        $user = $this->_request['user'];

        $validator = Validator::make(['user_id' => $user_id], [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN'] || $user_id == $user['id']) {
                $response_data = $this->user_settings_service->findByUserId($user_id);
            } else {
                $response_data['message'] = 'User settings not found!';
                $status_code = Response::HTTP_NOT_FOUND;
            }
        }

        return response()->json($response_data);
    }

    /**
     * Update user settings by user id.
     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateByUserId()
    {
        $status_code = Response::HTTP_OK;
        $request_data = $this->_request->all();
        $user = $this->_request['user'];
        $response_data = [];
        
        $validator = Validator::make($request_data, [
            'daily_calories' => 'required|numeric:true|min:1',
            'monthly_budget' => 'required|numeric:true|min:1'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $user_id = $user['id'];
            $user_settings = $this->user_settings_service->updateByUserId($user_id, $request_data);

            if ($user_settings) {
                $response_data['message'] = 'User settings updated successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }
        return response()->json($response_data, $status_code);
    }
}
