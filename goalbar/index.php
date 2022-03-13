<?php

require '../private/actions.php';

$action = $_REQUEST['action'];

if ($action === 'update') {
	getGoalBarData();
	return;
}

require 'goalbar.html';