<?php

require '../private/actions.php';

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($action === 'push-donation')
			pushDonation($_POST);
	} else {
		if ($action === 'test-donation')
			pushTestDonation();
		else if ($action === 'reset-donations')
			resetDonations();
	}

	header("Location: .");
	exit;
}

require 'dashboard.php';
