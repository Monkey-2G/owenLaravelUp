<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function createUser(array $data) : User
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
     * @param array $data (id, name, email)
     * @return User
     */
    public function updateUser(array $data) : User
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
            Log::info('[id : '.$data['id'].'] user update start.');
            
            $updatedUser = $this->userRepository->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('[id : '.$data['id'].'] user update failed. :: '. $e->getMessage());
            
            throw new Exception('[id : '.$data['id'].'] user update failed. :: '. $e->getMessage());
        }

        DB::commit();

        Log::info('[id : '.$data['id'].'] user update success.');

        return $updatedUser;
    }

    /**
     * user 삭제 및 삭제 이전 파라미터 유효성 검사
     * @param array $data (id)
     * @return User
     */
    public function deleteUser (array $data) : User
    {
        $validateData = Validator::make($data, [
            'id' => 'required'
        ]);

        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        DB::beginTransaction();

        try {
            Log::info('[id : '.$data['id'].'] user delete start.');

            $deletedUser = $this->userRepository->delete($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('[id : '.$data['id'].'] user delete failed. :: '. $e->getMessage());

            throw new Exception('[id : '.$data['id'].'] user delete failed. :: '. $e->getMessage());
        }

        DB::commit();

        Log::info('[id : '.$data['id'].'] user delete success.');

        return $deletedUser;
    }

    /**
     * user id로 조회
     * @param array $data (id)
     * @return User
     */
    public function selectById (array $data) : User
    {
        $validateData = Validator::make($data, [
            'id' => 'required'
        ]);

        if($validateData->fails())
        {
            throw new Exception($validateData->errors()->first());
        }

        try {
            Log::info('[id : '.$data['id'].'] user select start.');

            $selectdUser = $this->userRepository->getUserById($data);
        } catch (Exception $e) {
            Log::error('[id : '.$data['id'].'] user select failed. :: '. $e->getMessage());

            throw new Exception('[id : '.$data['id'].'] user select failed. :: '. $e->getMessage());
        }

        Log::info('[id : '.$data['id'].'] user select success.');

        return $selectdUser;
    }
}