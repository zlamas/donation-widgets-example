<?php

require '../private/actions.php';

$action = $_REQUEST['action'] ?? null;

if (isset($action)) {
	$isPost = $_SERVER['REQUEST_METHOD'] == 'POST';

	if ($action === 'test-donation')
		pushTestDonation();
	else if ($isPost && $action === 'push-donation')
		pushDonation($_POST);
	else if ($action === 'reset-donations')
		resetDonations();

	header("Location: .");
	return;
}

require 'dashboard.php';
