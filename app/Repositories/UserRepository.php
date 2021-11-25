<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserRepository {

    public function __construct(
        private User $user
    ) {

    }

    /**
     * user 생성
     * @param array $data (name, Email, password)
     * @return User
     */
    public function save(array $data) : User
    {
        $createUser = DB::transaction(function () use($data): User {
            $user = new $this->user;

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Crypt::encryptString($data['password']);
    
            $user->save();

            return $user;
        });
        
        return $createUser;
    }

    /**
     * user 정보 수정
     * @param array $data (name, email)
     * @return User
     */
    public function update (array $data) : User
    {
        $updateUser = DB::transaction(function () use ($data): User {
            try {
                $user = $this->getUserById($data); 

                $user->name = $data['name'];
                $user->update();
            } catch (Exception $e) {
                throw $e;
            }

            return $user;
        });

        return $updateUser;
    }

    /**
     * user 삭제
     * @param array $data (id)
     * @return User
     */
    public function delete(array $data) : User
    {
        $deletedUser = DB::transaction(function () use($data): User {
            try {
                $user = $this->getUserById($data);
                $user->delete();

            } catch (Exception $e) {
                throw $e;
            }
            return $user;         
        });

        return $deletedUser;
    }

    /**
     * PK인 id 값으로 user 조회
     * @param array $data (id)
     * @return User
     */
    public function getUserById(array $data) : User
    {
        return User::findOrFail($data['id']);
    }
}