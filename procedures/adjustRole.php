<?php
require_once '../includes/bootstrap.php';
requireAuth();

$userId = request()->get('userId');
$role = request()->get('role');

switch (strtolower($role)) {
    case 'promote':
        promote($userId);
        $session->getFlashBag()->add('success', "{$user['email']} Promoted to Admin!");
        break;
    case 'demote':
        demote($userId);
        $session->getFlashBag()->add('success', "{$user['email']} Demoted from Admin!");
        break;

}
redirect('../admin.php');
