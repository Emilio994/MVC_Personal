<?php

namespace Frame\Models;

class User extends BaseModel {

    protected string $modelName = 'users';
    private const SALT = 'HuGYg5_fG62';


    public function login(string $username, string $password) {
        
        $user = $this->exec(

            "SELECT *
            FROM {$this->modelName}
            WHERE username = :username
            AND password = :password",

            [
                ':username' => $username,
                ':password' => \crypt($password,static::SALT)
            ],

            false
        );
        
        if($user) {
            \refreshSession();
            $_SESSION['user'] = $user;
        }
        else header('Location: /login', true , 302);
    }
}