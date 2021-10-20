<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once 'lib/Database.php';
require_once 'lib/User.php';
require_once 'lib/Newsletter.php';

header('Content-Type: application/json; charset=utf-8');

// Sanitize POST array
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

$errors = [];
$data = [];

$name = $POST['name'];
$email = $POST['email'];
$form_id = $POST['form_id'];
$type = $POST['type'];

if (empty($name)) {
    $errors[] = 'Name is required.';
}
if (empty($email)) {
    $errors[] = 'Email is required.';
} else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email format is invalid.';
    }
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
} else {
    // Register user and subscribe to newsletter
    $user = new User();
    $user_id = $user->getID($email);
    $newsletter = new Newsletter();

    if ($user->isUserEmailExists($email)) {
        // 1. Check if user is already subscribed to the same subscription_type
        if ($newsletter->isSubscribed($user_id, $type)) {
            $data['success'] = true;
            $data['message'] = 'You\'re already subscibed to our newsletter';
        } else {
            $newsletter->create($user_id, $form_id, $type);
            $data['success'] = true;
            $data['message'] = 'Thank you for subscribing';
        }
    } else {
        // 1. Register to user table
        $user->create($name, $email);
        $user_id = $user->getID($email);
        
        // 2. Check if user is already subscribed to the same subscription_type
        if ($newsletter->isSubscribed($user_id, $type)) {
            $data['success'] = true;
            $data['message'] = 'You\'re already subscibed to our newsletter';
        } else {
            $newsletter->create($user_id, $form_id, $type);
            $data['success'] = true;
            $data['message'] = 'Thank you for subscribing';
        }
    }
}

echo json_encode($data, JSON_PRETTY_PRINT);
