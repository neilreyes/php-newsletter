<?php

use \Doctrine\DBAL\DriverManager;
    
class Newsletter
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($user_id, $form_id, $type)
    {
        $this->db->statement = $this->db->connection->prepare('INSERT INTO newsletter(user_id, form_id, type, active) VALUES(:user_id, :form_id, :type, :active)');
        $this->db->statement->bindValue('user_id', $user_id);
        $this->db->statement->bindValue('form_id', $form_id);
        $this->db->statement->bindValue('type', $type);
        $this->db->statement->bindValue('active', true);
        $this->db->result_set = $this->db->statement->executeQuery();
    }

    public function fetchAll()
    {
        $this->db->statement = $this->db->connection->prepare('SELECT * FROM newsletter');
        $this->db->result_set = $this->db->statement->executeQuery();

        return json_encode($this->db->result_set->fetchAllAssociative(), JSON_PRETTY_PRINT);
    }

    public function unSubscribe($user_id)
    {
        $this->db->statement = $this->db->connection->prepare('
			UPDATE newsletter SET active = :active WHERE user_id = :user_id
		');
        $this->db->statement->bindValue('user_id', $user_id);
        $this->db->statement->bindValue('active', false);
        $this->db->statement->executeQuery();

        return json_encode(["Message"=>"Unsubscribtion completed"]);
    }

    public function isSubscribed($user_id, $type)
    {
        $this->db->statement = $this->db->connection->prepare(
            'SELECT *
				FROM newsletter
				WHERE user_id = :user_id
					AND type = :type
					AND active = :active'
        );
        $this->db->statement->bindValue('user_id', $user_id);
        $this->db->statement->bindValue('type', $type);
        $this->db->statement->bindValue('active', true);
        $this->db->result_set = $this->db->statement->executeQuery();

        if ($this->db->result_set->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
