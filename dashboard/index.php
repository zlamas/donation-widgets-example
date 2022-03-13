<?php

require '../private/actions.php';

$action = $_REQUEST['action'];
$isPost = $_SERVER['REQUEST_METHOD'] == 'POST';

if ($action === 'test-donation') {
	pushTestDonation();
	return;
}

if ($isPost && $action === 'push-donation') {
	pushNewDonation();
	return;
}

if ($action === 'init') {
	initDashboard();
	return;
}

if ($action === 'reset-donations') {
	resetDonations();
	return;
}

require 'dashboard.html';