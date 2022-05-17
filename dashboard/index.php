<?php

require '../private/actions.php';

$action = $_REQUEST['action'] ?? null;
$isPost = $_SERVER['REQUEST_METHOD'] == 'POST';

if ($action === 'test-donation') {
	pushTestDonation();
	return;
}

if ($isPost && $action === 'push-donation') {
	pushDonation($_POST);
	return;
}

if ($action === 'reset-donations') {
	resetDonations();
	return;
}

require 'dashboard.php';
