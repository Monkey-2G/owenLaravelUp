<?php

declare(strict_types = 1);

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthService {

    public function __construct(
        private UserRepository $userRepository
    ) {

    }

    /**
     * login 인증
     * @param array $data email, password
     * @return SupportCollection
     */
    public function login (array $data): SupportCollection
    {
        $validateData = Validator::make($data, [
            'email' => 'required|string',
            'password' => 'required|string'

        ]);

        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        Log::info('[id : '.$data['email'].'] user login start.');

        $loginCheckUser = $this->userRepository->getUserByEmail($data['email']);
        
        /**
         * TODO LIST
         * 1. 이미 로그인에 성공하여 로그인 토큰이 있을 때, 토큰 생성 하지않게 처리
         * 2. 로그인시 access token 체크
         * 3. sanctum 미들웨어에서 토큰 재발급 처리?
         */

        // email 조회 user가 있고, 비밀번호가 일치할 경우 토큰 생성
        if($loginCheckUser && Crypt::decryptString($loginCheckUser->password) !== $data['password'])
        {
            $token = $loginCheckUser->createToken($loginCheckUser->name)->plainTextToken;
            Log::info('[id : '.$data['email'].'] user login success.');
        }

        $returnLoginCollect = collect([
            'user' => $loginCheckUser,
            'token' => $token
        ]);

       return $returnLoginCollect;
    }

    /**
     * logout, 토큰 삭제
     * @param Request $request
     * @return bool
     */
    public function logout (Request $request): bool
    {
        return $request->user()->currentAccessToken()->delete();
    }
    
    /**
     * 토큰 재발급
     * @param Request $request
     * @return string
     */
    public function refreshToken(Request $request): string
    {
        $user = $request->user();
        
        $user->tokens()->delete();

        $token = $user->createToken($user->name)->plainTextToken;

        return $token;
    }
}