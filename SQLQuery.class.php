<?php

/**
 * Class SQLQuery
 * Example:
 * $query = new SQLQuery();
 * $query->table = "tbl_user";
 * $query->condition = "user_id = 5";
 * $queryString = $query->selectQuery();
 */

class SQLQuery
{
    public $table;
    public $select = "*";
    public $condition;
    public $order;
    public $group;
    public $limit;
    public $offset;
    public $setParams; //key=field_name, value=value to set

    public function insertQuery()
    {

        $columns = array_keys($this->setParams);
        $values = array_values($this->setParams);

        foreach($values as $key => $value) {
            $values[$key] = "'".$value."'";
        }

        $columnsString = implode(",", $columns);
        $valuesString = implode(",", $values);

        return "INSERT INTO " . $this->table . " (" . $columnsString . ") VALUES (" . $valuesString . ")";
    }

    public function selectQuery()
    {
        $query = "SELECT " . $this->select . " FROM " . $this->table . " WHERE " . $this->condition;
        if ($this->offset) {
            $query." OFFSET ".$this->offset;
        }
        if ($this->limit) {
            $query." LIMIT ".$this->limit;
        }
        return $query;
    }

    public function updateQuery()
    {
        $setString = "";
        foreach ($this->setParams as $name => $value) {
            if ($setString == "") {
                $setString .= " " . $name . " = " . $value;
            } else {
                $setString .= ", " . $name . " = " . $value;
            }
        }
        return "UPDATE table_name SET " . $setString . " " . " WHERE " . $this->condition;
    }

    public function deleteQuery()
    {
        return "DELETE FROM " . $this->table . " WHERE " . $this->condition;
    }
}