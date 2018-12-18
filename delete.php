<?php
require_once 'db.class.php';
require_once 'user.class.php';

if(! isset($_GET['id'])) {
    header("Location: index.php");
    die();
}

if(! User::currentLoggedIn()) {
    echo "Not authorized";
    die();
}

$query = new SQLQuery();
$person_id = DB::instance()->escapeString($_GET['id']);
$query->condition = "person_id = {$person_id}";
DB::instance()->delete($query);
header("Location: index.php");
