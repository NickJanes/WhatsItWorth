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
$expTime = time() + 3600;

$jwt = \Firebase\JWT\JWT::encode([
    'iss' => request()->getBaseUrl(),
    'sub' => "{$user['id']}",
    'exp' => $expTime,
    'iat' => time(),
    'nbf' => time(),
    'is_admin' => $user['role_id'] == 1
], getenv("SECRET_KEY"),'HS256');

$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', $jwt, $expTime, '/', getenv('COOKIE_DOMAIN'));

$session->getFlashBag()->add('success', 'User Added and Successfully Logged In');
redirect('../index.php',['cookies' => [$accessToken]]);
