<?php
$mysqli = mysqli_connect("localhost");
$fetchUrl = "https://swapi.co/api/people";
do{
    $allPeople = json_decode(file_get_contents($fetchUrl), true);
    $persons = $allPeople['results'];
    foreach($persons as $person)
    {
        // Get only data column that are not array
        $dbColumns = [];
        foreach($persons as $key => $value)
        {
            if(is_array($value))
                continue;

            $dbColumns[$key] = "'".$value."'";
        }
        $mysqli->query("INERT INTO tbl_persons (person_id, ".implode(",", array_keys($dbColumns)).") VALUES (NULL, ".implode(",", $dbColumns).")");
        $fetchUrl = $allPeople['next'];
    }
} while ($fetchUrl != null);
