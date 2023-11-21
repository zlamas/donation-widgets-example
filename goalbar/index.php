<?php

require '../private/actions.php';

if (isset($_GET['action'])) {
	$action = $_GET['action'];

	if ($action === 'update')
		updateGoalBar();

	exit;
}

require 'goalbar.html';
