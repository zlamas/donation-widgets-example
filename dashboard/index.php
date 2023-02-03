<?php

require '../private/actions.php';

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($action === 'push-donation')
			pushDonation($_POST);
	} else switch ($action) {
		case 'test-donation':
			pushTestDonation();
			break;
		case 'reset-donations':
			resetDonations();
			break;
		case 'goalbar-settings':
			saveGoalBarSettings();
			break;
	}

	header("Location: .");
	exit;
}

require 'dashboard.php';
