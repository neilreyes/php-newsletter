<?php
use \Doctrine\DBAL\DriverManager;
use \Doctrine\DBAL\Driver\Statement;

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;
    
    public $connection;
    public $query_string;
    public $statement;
    public $result_set;

    public function __construct()
    {
        $this->connection = DriverManager::getConnection(array(
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'driver' => 'pdo_mysql',
        ));
    }
}
