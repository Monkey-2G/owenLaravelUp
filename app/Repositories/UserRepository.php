<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Crypt;

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
        $user = new $this->user;
        
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Crypt::encryptString($data['password']);

        $user->save();

        return $user->fresh();
    }

    /**
     * user 정보 수정
     * @param Array $data (name, email)
     * @return User
     */
    public function update (Array $data) : User
    {
        $user = $this->getUserById($data); 

        $user->name = $data['name'];
        $user->update();

        return $user->fresh();
    }

    /**
     * user 삭제
     * @param Array $data (id)
     * @return User
     */
    public function delete(Array $data) : User
    {
        $user = $this->getUserById($data);

        $user->delete();

        return $user;
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