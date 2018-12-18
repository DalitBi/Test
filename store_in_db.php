<?php
$mysqli = mysqli_connect("localhost", "root", "root", "swapi");
$person_attributes = [
    'name',
    'height',
    'hair_color',
    'skin_color',
    'eye_color',
    'birthyear',
    'gender',
];
$fetchUrl = "https://swapi.co/api/people";
do{
    $allPeople = json_decode(file_get_contents($fetchUrl), true);
    $persons = $allPeople['results'];
    foreach($persons as $person)
    {
        // Get only data column that are not array
        $dbColumns = [];
        foreach($person_attributes as $attribute)
        {
            $dbColumns[$attribute] = "'".$person[$attribute]."'";
        }
        $mysqli->query("INERT INTO tbl_person (person_id, ".implode(",", array_keys($dbColumns)).") VALUES (NULL, ".implode(",", $dbColumns).")");
        $fetchUrl = $allPeople['next'];
    }
} while ($fetchUrl != null);