<?php

require '../private/actions.php';

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];

	if ($action === 'update')
		updateAlertBox();

	exit;
}

require 'alertbox.html';
