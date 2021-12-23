<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    )
    {

    }

    /**
     * user login
     * @param Request $request
     * @return JsonResponse
     */
    public function login (Request $request): JsonResponse
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        $result = [
            'status' => 200,
            'msg' => 'Login Success'
        ];

        $result['result'] = $this->authService->login($data);

        return response()->json($result);
    }  

    /**
     * user logout
     * @param Request $request
     * @return JsonResponse
     */
    public function logout (Request $request): JsonResponse
    {  
        $result = [
            'status' => 200,
            'msg' => 'Logout Success'
        ];

        $result['result'] = $this->authService->logout($request);

        return response()->json($result);
    }
    
    /**
     * user logout
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh (Request $request): JsonResponse
    {  
        $result = [
            'status' => 200,
            'msg' => 'refreshToken Success'
        ];

        $result['result'] = $this->authService->refreshToken($request);

        return response()->json($result);
    }
}
