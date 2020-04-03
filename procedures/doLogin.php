<?php
require_once '../includes/bootstrap.php';

$user = findUserByEmail(request()->get('email'));
if (empty($user)) {
  $session->getFlashBag()->add('error', 'Email ' . $email . 'is not registered to an account.');
  redirect('../login.php');
}

if(!password_verify(request()->get('password'), $user['password'])) {
  $session->getFlashBag()->add('error', 'Invalid Password');
  redirect('../login.php');
}

$expTime = time() + 3600;

$jwt = \Firebase\JWT\JWT::encode([
  'iss' => request()->getBaseUrl(),
  'sub' => "{$user['id']}",
  'exp' => $expTime,
  'iat' => time(),
  'nbf' => time(),
  'is_admin' => $user['role_id'] == 1
], getenv("SECRET_KEY"), 'HS256');

$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', $jwt, $expTime, '/', getenv('COOKIE_DOMAIN'));

$session->getFlashBag()->add('success', 'Successfully Logged In');
redirect('../index.php', ['cookies' => [$accessToken]]);
