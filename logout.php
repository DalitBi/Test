<?php
require 'user.class.php';

// Logout user
$user = User::currentLoggedIn();
$user->logout();

// Redirect to homepage
header("Location: index.php");
