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
            'msg' => 'Create Success'
        ];

        try {
            $result['data'] = $this->userService->createUser($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'msg' => 'Create Fail',
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
            'msg' => 'Update Success'
        ];
        
        try {
            $result['data'] = $this->userService->updateUser($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'msg' => 'Update Fail',
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result);
    } 

    /**
     * user 삭제
     * @param Request $request
     * @return JsonResponse
     */
    public function delete (Request $request) : JsonResponse
    {
        $data = $request->only([
            'id'
        ]);

        $result = [
            'status' => 200,
            'msg' => 'Delete Success'
        ];

        try {
            $result['data'] = $this->userService->deleteUser($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'msg' => 'Delete Fail',
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result);
    }

    /**
     * user id로 조회
     * @param Request $request
     * @return JsonResponse
     */
    public function selectById(Request $request) : JsonResponse
    {
        $data = $request->only([
            'id'
        ]);

        $result = [
            'status' => 200,
            'msg' => 'Select Success'
        ];

        try {
            $result['data'] = $this->userService->SelectById($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'msg' => 'Select Fail',
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
    }
}
