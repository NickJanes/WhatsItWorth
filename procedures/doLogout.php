<?php
require_once '../includes/bootstrap.php';

$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', "Expired", time()-3600, '/', getenv('COOKIE_DOMAIN'));
redirect('../index.php', ['cookies' => [$accessToken]]);

 ?>
