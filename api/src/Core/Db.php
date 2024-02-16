<?php

namespace App\Core;

use PDO;

/**
 * Class for making database queries
 */
class Db
{

    const TABLE  = 'subscribers';

    /**
     * Database name
     *
     * @var string
     */
    private $dbName;
    
    /**
     * Database host
     *
     * @var string
     */
    private $host;

    /**
     * Database port
     *
     * @var string
     */
    private $port;

    /**
     * Database username
     *
     * @var string
     */
    private $username;

    /**
     * Database password
     *
     * @var string
     */
    private $password;

    /**
     * Database connection
     *
     * @var PDO
     */
    public $connection;

    /**
     * Datbase class instance
     *
     * @var Db
     */
    private static $instance;

    /**
     * Gets mysql db connection data from env and attempts connection
     *
     * @return void
     */
    public function __construct()
    {
        $this->dbName = getenv('MYSQL_DATABASE');
        $this->username = getenv('MYSQL_USER');
        $this->password = getenv('MYSQL_PASSWORD');
        $this->host = getenv('MYSQL_HOST');
        $this->port = getenv('MYSQL_PORT');

        $this->connection = $this->setup();
    }

    /**
     * Connect with MySql server and returns PDO connection
     *
     * @return PDO
     */
    private function setup(): PDO
    {
        //Create connection
        $connection = new PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $this->host,
                $this->port,
                $this->dbName,
            ),
            $this->username,
            $this->password,
        );

        // Only show error type exceptions
        $connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        return $connection;
    }

    /**
     * Create subscribers table if not exist
     *
     * @return void
     */
    public function createTable(): void
    {
        $query = $this->connection->prepare('
            CREATE TABLE IF NOT EXISTS `' . $this->dbName. '`.`'. self::TABLE .'` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `email` VARCHAR(45) CHARACTER SET "utf8" NOT NULL,
                `firstName` VARCHAR(45) NULL,
                `lastName` VARCHAR(45) NULL,
                `status` TINYINT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `email_UNIQUE` (`email` ASC))');

        $query->execute();
    }

    /**
     * Get an instance of PDO
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance == null) {
            self::$instance = new Db();
        }
 
        return self::$instance->setup();
    }
}
