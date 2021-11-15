<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * user 생성
     * @param Request $request
     * @return JsonResponse
     */
    public function create (Request $request) : JsonResponse
    {
        $data = $request->only([
            'name',
            'email',
            'password'
        ]);

        $result = [
            'status' => 200,
            'msg' => 'Success'
        ];

        try {
            $result['data'] = $this->userService->createUser($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'msg' => 'fail',
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result);
    }

    /**
     * user 정보 (name, email) 수정
     * @param Request $request
     * @return JsonResponse
     */
    public function update (Request $request) : JsonResponse
    {
        $data = $request->only([
            'id',
            'name',
            'email'
        ]);

        $result = [
            'status' => 200,
            'msg' => 'Success'
        ];
        
        try {
            $result['data'] = $this->userService->updateUser($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'msg' => 'fail',
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result);
    } 
}
