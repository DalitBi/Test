<?php
require_once 'user.class.php';
require_once 'db.class.php';

$error = null;
if(! empty($_POST['username']) && ! empty($_POST['password']))
{
    $username = DB::instance()->escapeString($_POST['username']);
    $password = DB::instance()->escapeString($_POST['password']);
    if(User::login($username, $password))
        header("Location: index.php");
    else
        $error = "Login failed";
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Log In</title>
</head>
<body>

<h1>Log In</h1>
<? if($error !== null): ?>
    <?= $error ?>
<? endif; ?>

<form method="post">
    <div class="form-group">
        User Name: <input type="text" name="username"  />
    </div>
    <div class="form-group">
        Password: <input type="password" name="password" />
    </div>
    <input type="submit" name="login" value="Login">
</form>
</body>
</html>
