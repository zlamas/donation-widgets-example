<?php

require '../private/actions.php';

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];

	if ($action === 'update')
		getAlertBoxData();

	exit;
}

require 'alertbox.html';
