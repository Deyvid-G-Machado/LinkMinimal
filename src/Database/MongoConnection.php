<?php

namespace App\Database;

use MongoDB\Client;
use Dotenv\Dotenv;

class MongoConnection
{
    private $client;
    private $database;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '27017';
        $username = $_ENV['DB_USERNAME'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $database = $_ENV['DB_DATABASE'] ?? 'test';

        $uri = "mongodb://";
        if (!empty($username) && !empty($password)) {
            $uri .= "{$username}:{$password}@";
        }
        $uri .= "{$host}:{$port}";

        try {
            $this->client = new Client($uri);
            $this->database = $this->client->selectDatabase($database);
        } catch (\Exception $e) {
            die('Erro ao conectar ao MongoDB: ' . $e->getMessage());
        }
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
