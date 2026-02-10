<?php
namespace App\Repository;

use App\Entity\User;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username=:u");
        $stmt->execute(['u'=>$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $user = new User($row['username'], $row['password']);
        $user->id = $row['id'];
        return $user;
    }

    public function save(User $user): bool
    {
        if ($user->id) {
            $stmt = $this->pdo->prepare("UPDATE users SET password=:p WHERE id=:id");
            return $stmt->execute(['p'=>$user->password, 'id'=>$user->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:u,:p)");
            $result = $stmt->execute(['u'=>$user->username, 'p'=>$user->password]);
            $user->id = (int)$this->pdo->lastInsertId();
            return $result;
        }
    }
}
