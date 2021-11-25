<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Crypt;

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
        $user = new $this->user;
        
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Crypt::encryptString($data['password']);

        $user->save();

        return $user->fresh();
    }

    /**
     * user 정보 수정
     * @param array $data (name, email)
     * @return User
     */
    public function update (array $data) : User
    {
        $user = $this->getUserById($data); 

        $user->name = $data['name'];
        $user->update();

        return $user->fresh();
    }

    /**
     * user 삭제
     * @param array $data (id)
     * @return User
     */
    public function delete(array $data) : User
    {
        $user = $this->getUserById($data);

        $user->delete();

        return $user;
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