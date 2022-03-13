<?php

require '../private/actions.php';

$action = $_REQUEST['action'];

if ($action === 'update') {
	getAlertBoxData();
	return;
}

require 'alertbox.html';