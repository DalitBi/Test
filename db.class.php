<?php

require_once 'SQLQuery.class.php';

class DB
{
    private static $inst;
    private $mysqliConnection;

    private function __construct()
    {
        $this->mysqliConnection = new mysqli("localhost", "root", "root", "swapi");
    }

    public function escapeString($string)
    {
        return $this->mysqliConnection->real_escape_string($string);
    }

    public static function instance()
    {
        if (self::$inst == NULL) {
            self::$inst = new self();
        }
        return self::$inst;
    }

    public function getRows(SQLQuery $query)
    {
        /* return arrays of rows, where each row is array of [key => value] of attributes */
        $resArray = [];
        if ($result = $this->mysqliConnection->query($query->selectQuery())) {
            while (($row = $result->fetch_assoc())) {
                $resArray[] = $row;
            }
        }
        return $resArray;
    }

    public function getRow(SQLQuery $query)
    {
        // returns array of attributes of single row key=>value
        if ($result = $this->mysqliConnection->query($query->selectQuery())) {
            return ($result->fetch_assoc());
        }
        return NULL;
    }

    public function update(SQLQuery $query)
    {
        if ($this->mysqliConnection->query($query->updateQuery())) {
            return true;
        }
        return false;
    }

    public function insert(SQLQuery $query)
    {
        if ($this->mysqliConnection->query($query->insertQuery())) {
            return true;
        }
        return false;
    }

    public function delete(SQLQuery $query)
    {
        if ($this->mysqliConnection->query($query->deleteQuery())) {
            return true;
        }
        return false;
    }
}