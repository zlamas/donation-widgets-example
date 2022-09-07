<?php

require '../private/actions.php';

$action = $_REQUEST['action'] ?? null;

if ($action === 'update') {
	getAlertBoxData();
	exit;
}

require 'alertbox.html';
