<?php
declare(strict_types=1);

namespace App;

class Authorization
{
    private Database $database;
    /**
     * Undocumented function
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    /**
     * Authoriztion function register
     *
     * @param array $data
     * @return boolean
     * @throws AuthorizationException
     */
    public function register(array $data): bool
    {
        if (empty($data['username'])){
            throw new AuthorizationException('The Username should not be empty');
        }
        if (empty($data['email'])){
            throw new AuthorizationException('The Email should not be empty');
        }
        if (empty($data['password'])){
            throw new AuthorizationException('The Password should not be empty');
        }
        if ($data['password'] !==$data['confirm_password']){
            throw new AuthorizationException('The Password and Confirm Pssword should match');
        }
        
        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM user WHERE email = :email'
        );
        $statement->execute([
            'email' => $data['email']
        ]);
        $user = $statement->fetch();
        if (!empty($user)){
            throw new AuthorizationException('User with such email exists');
        }

        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM user WHERE username = :username'
        );
        $statement->execute([
            'username' => $data['username']
        ]);
        $user = $statement->fetch();
        if (!empty($user)){
            throw new AuthorizationException('User with such username exists');
        }

        $statement = $this->database->getConnection()->prepare(
            'INSERT INTO user (email, username, password) VALUES (:email, :username, :password)'
        );

        $statement->execute([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);
        return true;
    }
}