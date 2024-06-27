<?php

require 'private/actions.php';

$location = 'dashboard/';

if (isset($_POST['action'])) {
	$action = $_POST['action'];
	unset($_POST['action']);
	$location .= '?tab=2';

	switch ($action) {
	case 'push-donation':
		push_donation($_POST);
		break;
	case 'alertbox-save':
		save_alertbox_settings($_POST);
		break;
	case 'goalbar-save':
		save_goalbar_settings($_POST);
		break;
	case 'goalbar-reset':
		reset_goalbar();
		break;
	case 'reset-donations':
		reset_donations();
		break;
	case 'test-donation':
		push_test_donation();
		break;
	}
} else if (isset($_GET['action'])) {
	$action = $_GET['action'];

	switch ($action) {
	case 'goalbar-update':
		update_goalbar();
		break;
	case 'alertbox-update':
		update_alertbox();
		break;
	}

	exit;
}

header("Location: $location");
