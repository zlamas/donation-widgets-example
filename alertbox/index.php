<?php

require '../private/actions.php';

$action = $_REQUEST['action'] ?? null;

if ($action === 'update') {
	getAlertBoxData();
	return;
}

require 'alertbox.html';
