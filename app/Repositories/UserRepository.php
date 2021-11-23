<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserRepository {

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * user 생성
     * @param Array $data (name, Email, password)
     * @return User
     */
    public function save(Array $data) : User
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
     * @param Array $data (name, email)
     * @return User
     */
    public function update (Array $data) : User
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
     * @param Array $data (id)
     * @return User
     */
    public function delete(Array $data) : User
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
     * @param Array $data (id)
     * @return User
     */
    public function getUserById(Array $data) : User
    {
        return User::findOrFail($data['id']);
    }
}