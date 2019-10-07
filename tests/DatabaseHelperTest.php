<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class DatabaseHelperTest extends TestCase
{
    /**
     * @var DatabaseHelper
     */
    private $database_helper;

    public function setUp()
    {
        $this->database_helper = new DatabaseHelper(new Database());
    }

    /**
     * @test
     */
    public function check_is_all_tables_are_empty()
    {
        $this->database_helper->truncateAllTables();

        $result = $this->database_helper->areTablesEmpty();

        $this->assertTrue($result);
    }
}
