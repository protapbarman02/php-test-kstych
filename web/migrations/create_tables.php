<?php

require_once __DIR__ . '/../utils/Database.php';

class CreateTable
{
    
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    private function createUsesrTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                roles TEXT[] NOT NULL
            )
        ";

        $this->executeQuery($sql, 'users');
    }
    
    private function createRecipesTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS recipes (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                prep_time INT NOT NULL,
                difficulty INT NOT NULL CHECK (difficulty BETWEEN 1 AND 3),
                vegetarian BOOLEAN NOT NULL
            )
        ";

        $this->executeQuery($sql, 'recipes');
    }

    private function createRatingsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS recipes (
                id SERIAL PRIMARY KEY,
                recipe_id INT NOT NULL,
                created_by INT,
                rating INT CHECK (rating BETWEEN 1 AND 5),
                FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            )
        ";

        $this->executeQuery($sql, 'ratings');
    }

    private function executeQuery($sql, $table)
    {
        try {
            $this->db->exec($sql);
            echo "Table '$table' created successfully.\n";
        } catch (PDOException $e) {
            echo "Error creating table '$table': " . $e->getMessage() . "\n";
        }
    }

    public function execute()
    {
        $this->createUsesrTable();
        $this->createRecipesTable();
        $this->createRatingsTable();
    }

}

$createTables = new CreateTable();
$createTables->execute();