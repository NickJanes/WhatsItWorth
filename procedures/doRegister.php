<?php
require_once '../includes/bootstrap.php';

$username = request()->get('username');
$email = request()->get('email');
$password = request()->get('password');
$confirmpassword = request()->get('confirm_password');

if ($password != $confirmpassword) {
  $session->getFlashBag()->add('error', 'Passwords do NOT match');
  redirect('../register.php');
}

$user = findUserByUsername($username);
if (!empty($user)) {
  $session->getFlashBag()->add('error', 'The username ' . $username . ' already exists.');
  redirect('../register.php');
}

$user = findUserByEmail($email);
if (!empty($user)) {
  $session->getFlashBag()->add('error', 'Email already in use by another account.');
  redirect('../register.php');
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$user = createUser($username, $email, $hashed);
$session->getFlashBag()->add('success', 'User Added');
redirect('../index.php');
