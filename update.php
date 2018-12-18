<?php
require_once 'db.class.php';
require_once 'user.class.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    die();
}

$person_attributes = [
    'name',
    'height',
    'hair_color',
    'skin_color',
    'eye_color',
    'birthyear',
    'gender',
];

$person_id = DB::instance()->escapeString($_GET['id']);
$error = null;
if(isset($_POST['Person']))
{
    $personData = $_POST['Person'];
    foreach($person_attributes as $attribute)
    {
        if(empty($personData[$attribute]))
        {
            $error = "All fields are required";
            break;
        }
        $personData[$attribute] = DB::instance()->escapeString($personData[$attribute]);
    }

    if($error === null) {
        $query = new SQLQuery();
        $query->table = 'tbl_persons';
        $query->condition = "person_id = {$person_id}";
        $query->setParams = $personData;
        DB::instance()->update($query);
        header("Location: index.php");
    }
}

$query = new SQLQuery();
$query->table = "tbl_persons";
$query->condition = "person_id = {$person_id}";
$person = DB::instance()->getRow($query);
?>
<html>
<head>
    <title>Update Person</title>
</head>
<body>
<h1>Update person <?= $person->name ?></h1>

<? if($error !== null): ?>
    <span class="error"><?= $error ?></span>
<? endif; ?>

<form method="post">
    <?php foreach($person_attributes as $attribute): ?>
        <div class="form-group">
            <label for="<?= $attribute ?>_field"><?= $attribute ?></label>
            <input id="<?= $attribute ?>_field" type="text" name="Person[<?= $attribute ?>]" value="<?= $person->$attribute ?>" required="required" />
        </div>
    <?php endforeach; ?>

    <input type="submit" value="Save" />
</form>
</body>
</html>
