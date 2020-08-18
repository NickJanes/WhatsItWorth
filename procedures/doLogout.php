<?php
require_once '../includes/bootstrap.php';

$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', "Expired", time()-3600, '/', getenv('COOKIE_DOMAIN'));
$session->getFlashBag()->add('success', 'Successfully Logged Out');
redirect('../index.php', ['cookies' => [$accessToken]]);

 ?>
