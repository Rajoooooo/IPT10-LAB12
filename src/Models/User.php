<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class User extends BaseModel
{
    public function save($data) {
        $sql = "INSERT INTO users 
                SET
                    complete_name=:complete_name,
                    email=:email,
                    `password`=:password"; // Changed to match your column name
        $statement = $this->db->prepare($sql);
        $password_hash = $this->hashPassword($data['password']);
        $statement->execute([
            'complete_name' => $data['complete_name'],
            'email' => $data['email'],
            'password' => $password_hash // Changed to match your column name
        ]);
    
        return $this->db->lastInsertId();
    }

    protected function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyAccess($email, $password)
    {
        $sql = "SELECT password FROM users WHERE email = :email"; // Ensure this matches your table
        $statement = $this->db->prepare($sql);
        $statement->execute(['email' => $email]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if (empty($result)) {
            return false;
        }

        return password_verify($password, $result['password']); // Check against the 'password' column
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email"; // Fetch user details
        $statement = $this->db->prepare($sql);
        $statement->execute(['email' => $email]);
        return $statement->fetch(PDO::FETCH_ASSOC); // Return user data
    }
}
