<?php
namespace App\Repository;

use App\Entity\Score;
use App\Service\DatabaseService;

class ScoreRepository
{
    private DatabaseService $db;

    public function __construct(DatabaseService $db)
    {
        $this->db = $db;
    }

    public function findByUserId(int $userId): ?Score
    {
        $stmt = $this->db->getPDO()->prepare("SELECT * FROM scores WHERE user_id=:u");
        $stmt->execute(['u'=>$userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;

        $score = new Score($row['user_id'], $row['score']);
        $score->id = $row['id'];
        return $score;
    }

    public function save(Score $score): bool
    {
        if ($score->id) {
            $stmt = $this->db->getPDO()->prepare("UPDATE scores SET score=:s WHERE id=:id");
            return $stmt->execute(['s'=>$score->score, 'id'=>$score->id]);
        } else {
            $stmt = $this->db->getPDO()->prepare("INSERT INTO scores (user_id, score) VALUES (:u,:s)");
            $result = $stmt->execute(['u'=>$score->userId, 's'=>$score->score]);
            $score->id = (int)$this->db->getPDO()->lastInsertId();
            return $result;
        }
    }

    public function getTop5(): array
    {
        $stmt = $this->db->getPDO()->query("
            SELECT u.username, MAX(s.score) AS score
            FROM scores s
            JOIN users u ON u.id = s.user_id
            GROUP BY u.username
            ORDER BY score DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
