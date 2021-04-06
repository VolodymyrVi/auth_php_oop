<?php
declare(strict_types=1);

namespace App;

class Authorization
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function register(array $data): bool
    {
        return true;
    }
}