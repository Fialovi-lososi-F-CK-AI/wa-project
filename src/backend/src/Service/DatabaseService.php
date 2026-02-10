<?php
namespace App\Service;

use PDO;

class DatabaseService
{
    private PDO $pdo;

    public function __construct()
    {
        $databaseUrl = getenv('DATABASE_URL');
        if (!$databaseUrl) {
            throw new \Exception('DATABASE_URL not set in environment');
        }

        $parts = parse_url($databaseUrl);
        if (!$parts) {
            throw new \Exception('Invalid DATABASE_URL');
        }

        $host = $parts['host'];
        $port = $parts['port'] ?? 5432;
        $user = $parts['user'];
        $pass = $parts['pass'];
        $db   = ltrim(explode('?', $parts['path'])[0], '/');

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
