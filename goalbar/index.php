<?php

require '../private/actions.php';

$action = $_REQUEST['action'] ?? null;

if ($action === 'update') {
	getGoalBarData();
	return;
}

require 'goalbar.html';
