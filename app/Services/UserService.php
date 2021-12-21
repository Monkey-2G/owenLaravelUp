<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserService {

    public function __construct(
        private UserRepository $userRepository
    ) {

    }

    /**
     * user 생성 및 생성 이전 파라미터 유효성 검사
     * @param array $data (name, email, password)
     * @return User
     */
    public function createUser(array $data): User
    {
        $validateData = Validator::make($data, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        Log::info('user create start.');

        $createdUser = $this->userRepository->save($data);

        Log::info('[id : '.$createdUser['id'].'] user create success.');


        return $createdUser;
    }

    /**
     * user 정보 수정 및 수정 이전 파라미터 유효성 검사
     * @param array $data (id, name, email)
     * @return User
     */
    public function updateUser(array $data): User
    {
        $validateData = Validator::make($data, [
            'id' => 'required',
            'name' => 'required',
            'email' => 'required'
        ]);
        
        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        Log::info('[id : '.$data['id'].'] user update start.');

        $updatedUser = $this->userRepository->update($data);

        Log::info('[id : '.$data['id'].'] user update success.');

        return $updatedUser;
    }

    /**
     * user 삭제 및 삭제 이전 파라미터 유효성 검사
     * @param array $data (id)
     * @return User
     */
    public function deleteUser (array $data): User
    {
        $validateData = Validator::make($data, [
            'id' => 'required'
        ]);

        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        Log::info('[id : '.$data['id'].'] user delete start.');

        $deletedUser = $this->userRepository->delete($data);

        Log::info('[id : '.$data['id'].'] user delete success.');

        return $deletedUser;
    }

    /**
     * user id로 조회
     * @param array $data (id)
     * @return User
     */
    public function selectById (array $data): User
    {
        $validateData = Validator::make($data, [
            'id' => 'required'
        ]);

        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        Log::info('[id : '.$data['id'].'] user select start.');

        $selectdUser = $this->userRepository->getUserById($data['id']);

        Log::info('[id : '.$data['id'].'] user select success.');

        return $selectdUser;
    }

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
         */

        // email 조회 user가 있고, 비밀번호가 일치할 경우 토큰 생성
        if($loginCheckUser && Crypt::decryptString($loginCheckUser->password) !== $data['password'])
        {
            $token = $loginCheckUser->createToken('login-token')->plainTextToken;
            Log::info('[id : '.$data['email'].'] user login success.');
        }

        $returnLoginCollect = collect([
            'user' => $loginCheckUser,
            'token' => $token
        ]);

       return $returnLoginCollect;
    }
}