<?php

declare(strict_types=1);

namespace Tests;

use mysqli;

final class Database
{
    /**
     * @var $mysqli mysqli
     */
    public $mysqli;

    public function __construct()
    {
            $dbServer = 'db';
            $dbUser = 'test';
            $dbPass = 'test';
            $dbName = 'test';
            $dbPort = 3306;

        $this->mysqli = new mysqli($dbServer, $dbUser, $dbPass, $dbName, $dbPort);

        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
        }
        $this->mysqli->set_charset("utf8");
    }

    public function fetch(string $query) : ?array
    {
        $stmt = $this->mysqli->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->store_result();
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $data;
    }

    public function execute(string $query)
    {
        $this->mysqli->multi_query($query);
        while ($this->mysqli->more_results() && $this->mysqli->next_result()) {;}
    }

}