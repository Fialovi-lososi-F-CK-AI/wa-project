<?php
namespace App\Repository;

use App\Entity\Score;
use PDO;

class ScoreRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUserId(int $userId): ?Score
    {
        $stmt = $this->pdo->prepare("SELECT * FROM scores WHERE user_id=:u");
        $stmt->execute(['u'=>$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $score = new Score($row['user_id'], $row['score']);
        $score->id = $row['id'];
        return $score;
    }

    public function save(Score $score): bool
    {
        if ($score->id) {
            $stmt = $this->pdo->prepare("UPDATE scores SET score=:s WHERE id=:id");
            return $stmt->execute(['s'=>$score->score, 'id'=>$score->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO scores (user_id, score) VALUES (:u,:s)");
            $result = $stmt->execute(['u'=>$score->userId, 's'=>$score->score]);
            $score->id = (int)$this->pdo->lastInsertId();
            return $result;
        }
    }

    public function getTop5(): array
    {
        $stmt = $this->pdo->query("
            SELECT u.username, MAX(s.score) AS score
            FROM scores s
            JOIN users u ON u.id = s.user_id
            GROUP BY u.username
            ORDER BY score DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
