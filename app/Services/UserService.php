<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserService {

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * user 생성 및 생성 이전 파라미터 유효성 검사
     * @param Array $data (name, email, password)
     * @return User
     */
    public function createUser(Array $data) : User
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

        return $this->userRepository->save($data);
    }

    /**
     * user 정보 수정 및 수정 이전 파라미터 유효성 검사
     * @param Array $data (id, name, email)
     * @return User
     */
    public function updateUser(Array $data) : User
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

        DB::beginTransaction();

        try {
            Log::info('user update start.');
            
            $updatedUser = $this->userRepository->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            
            throw new Exception('user update failed.');

            Log::error('user update failed.');
        }

        DB::commit();

        Log::info('user update success.');

        return $updatedUser;
    }
}