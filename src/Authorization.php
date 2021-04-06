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
            throw new AuthorizationException('The username should not be empty');
        }
        return true;
    }
}