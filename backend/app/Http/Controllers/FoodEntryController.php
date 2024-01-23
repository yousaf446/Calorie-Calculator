<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\FoodEntryService;
use Symfony\Component\HttpFoundation\Response;

class FoodEntryController extends Controller
{
    protected $foodentry_service;
    public function __construct(Request $request, FoodEntryService $foodentry_service)
    {
        parent::__construct($request);
        $this->foodentry_service = $foodentry_service;
    }

    /**
     * Finds all food entries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status_code = Response::HTTP_OK;
        $user = $this->_request['user'];

        if ($user['role'] == $this->calories_constants['ROLES']['ADMIN']) {
            $data = $this->foodentry_service->findAll();
        } else {
            $data = $this->foodentry_service->findByUserId($user['id']);
        }
       
        return response()->json($data, $status_code);
    }

    /**
     * Create a new food entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // validate incoming request
        $status_code = Response::HTTP_OK;
        $request_data = $this->_request->all();
        $user = $this->_request['user'];
        $response_data = [];

        $validator = Validator::make($request_data, [
            'product_name' => 'required|max:200',
            'consumed_at' => 'required|date|date_format:Y-m-d H:i:s',
            'calorie_value' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $request_data['user_id'] = $user['id'];
            $food_entry = $this->foodentry_service->store($request_data);

            if ($food_entry) {
                $response_data['message'] = 'FoodEntry created successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }
        return response()->json($response_data, $status_code);
    }

    /**
     * Get food entry by id.
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
            'id' => 'required|exists:food_entry'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN']) {
                $response_data = $this->foodentry_service->findById($id);
            } else {
                $user_id = $user['id'];
                $response_data = $this->foodentry_service->findOneByUserId($user_id, $id);
            }
        }

        if (!$response_data) {
            $response_data['message'] = 'Food entry not found!';
            $status_code = Response::HTTP_NOT_FOUND;
        }
        
        return response()->json($response_data, $status_code);
    }

    /**
     * Update food entry by id
     *
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
            'id' => 'required|exists:food_entry',
            'product_name' => 'required|min:2|max:200',
            'consumed_at' => 'required|date|date_format:Y-m-d H:i:s|before_or_equal:' . date('Y-m-d H:i:s', strtotime('+5 hours')),
            'calorie_value' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $id = $request_data['id'];
            unset($request_data['id']);

            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN']) {
                $food_entry = $this->foodentry_service->update($id, $request_data);
            } else {
                $user_id = $user['id'];
                $food_entry = $this->foodentry_service->updateByUser($user_id, $id, $request_data);
            }

            if ($food_entry) {
                $response_data['message'] = 'Food entry updated successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }
        return response()->json($response_data, $status_code);
    }

    /**
     * Delete food entry by id
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
            'id' => 'required|exists:food_entry',
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            if ($user['role'] == $this->calories_constants['ROLES']['ADMIN']) {
                $food_entry = $this->foodentry_service->deleteById($id);
            } else {
                $user_id = $user['id'];
                $food_entry = $this->foodentry_service->deleteByUser($user_id, $id);
            }

            if ($food_entry) {
                $response_data['message'] = 'Food entry deleted successfully.';
            } else {
                $response_data['message'] = 'Internal server error!';
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }

        return response()->json($response_data, $status_code);
    }

    /**
     * Get User Constraints
     *
     * @return \Illuminate\Http\Response
     */
    public function userConstraints()
    {
        $status_code = Response::HTTP_OK;
        $request_data = $this->_request->all();
        $response_data = [];

        $user = $this->_request['user'];
        if ($user['role'] != $this->calories_constants['ROLES']['ADMIN']) {
            $request_data['user_id'] = $user['id'];
        }

        $validator = Validator::make($request_data, [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $user_id = $request_data['user_id'];
            $date = $request_data['date'];
            $response_data = $this->foodentry_service->userConstraints($user_id, $date);
        }

        return response()->json($response_data, $status_code);
    }

    /**
     * Get Admin Report
     *
     * @param  Date  $date
     * @return \Illuminate\Http\Response
     */
    public function getAdminReport($date)
    {
        $status_code = Response::HTTP_OK;
        $response_data = [];

        $validator = Validator::make(['date' => $date], [
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            $response_data['errors'] = $validator->errors();
            $response_data['message'] = 'Bad request!';
            $status_code = Response::HTTP_BAD_REQUEST;
        } else {
            $response_data =  $this->foodentry_service->adminReport($date);
        }

        return response()->json($response_data, $status_code);
    }
}
