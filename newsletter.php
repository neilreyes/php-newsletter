<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'lib/Database.php';
require_once 'lib/Newsletter.php';

header('Content-Type: application/json; charset=utf-8');
$nl = new Newsletter();

// Get all newsletter entries
echo $nl->fetchAll();
