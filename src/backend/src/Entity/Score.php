<?php
namespace App\Entity;

class Score
{
    public ?int $id = null;
    public int $userId;
    public int $score;

    public function __construct(int $userId, int $score)
    {
        $this->userId = $userId;
        $this->score = $score;
    }
}
