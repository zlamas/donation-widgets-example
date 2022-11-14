<?php

require '../private/actions.php';

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
	$isPost = $_SERVER['REQUEST_METHOD'] == 'POST';

	if ($action === 'test-donation')
		pushTestDonation();
	else if ($isPost && $action === 'push-donation')
		pushDonation($_POST);
	else if ($action === 'reset-donations')
		resetDonations();

	header("Location: .");
	exit;
}

require 'dashboard.php';
