<?php

use \Doctrine\DBAL\DriverManager;

class User
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($name, $email)
    {
        $this->db->statement = $this->db->connection->prepare("INSERT INTO users(name, email) VALUES(:name, :email)");
        $this->db->statement->bindValue('name', $name);
        $this->db->statement->bindValue('email', $email);
        $this->db->result_set = $this->db->statement->executeQuery();
    }

    public function fetchAll()
    {
        $this->db->query_string = 'SELECT * FROM users';
        $this->db->statement = $this->db->connection->prepare($this->db->query_string);
        $this->db->result_set = $this->db->statement->executeQuery();

        return json_encode($this->db->result_set->fetchAllAssociative(), JSON_PRETTY_PRINT);
    }

    public function getID($email)
    {
        $this->db->statement = $this->db->connection->prepare('SELECT ID FROM users WHERE email = :email');
        $this->db->statement->bindValue('email', $email);
        $this->db->result_set = $this->db->statement->executeQuery();

        return $this->db->result_set->fetchOne();
    }

    public function isUserEmailExists($email)
    {
        $this->db->statement = $this->db->connection->prepare('SELECT * FROM users WHERE email = :email');
        $this->db->statement->bindValue('email', $email);
        $this->db->result_set = $this->db->statement->executeQuery();

        if ($this->db->result_set->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isUserID($id)
    {
        $this->db->statement = $this->db->connection->prepare('SELECT * FROM users WHERE id = :id');
        $this->db->statement->bindValue('id', $id);
        $this->db->result_set = $this->db->statement->executeQuery();

        if ($this->db->result_set->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($user_id)
    {
        $id = $user_id;
        $this->db->statement = $this->db->connection->prepare('DELETE FROM users WHERE id = :id');
        $this->db->statement->bindValue('id', $id);
        $this->db->result_set = $this->db->statement->executeQuery();
        
        return json_encode(array("Message"=>"You're account".$id." has been removed"));
    }
}
