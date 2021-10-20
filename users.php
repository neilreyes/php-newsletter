<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'lib/Database.php';
require_once 'lib/User.php';

header('Content-Type: application/json; charset=utf-8');
$user = new User();

// Get all users
echo $user->fetchAll();
//echo $user->delete(25);
