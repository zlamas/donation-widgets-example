<?php

require 'function.php';

function getGoalBarData() {
	$settings = getSettings();
	$data = $settings['goalbar'];
	$donations = filterById(getDonations(), $data['from']);

	$data['amount'] = array_sum(array_column($donations, "amount"));
	$data['currency'] = $settings['currency'];

	echo encodeJSON($data);
}

function getAlertBoxData() {
	$settings = getSettings()['alertbox'];
	$fromId = $_GET['from'];
	$donations = getDonations();

	if ($donations) {
		$updates = filterById($donations, $fromId);
		$settings['id'] = end($updates)['id'] ?? end($donations)['id'];
	} else {
		$updates = [];
		$settings['id'] = -1;
	}

	$data = [
		'settings' => $settings,
		'updates' => $updates
	];

	echo encodeJSON($data);
}

function initDashboard() {
	$data = [
		'settings' => getSettings(),
		'donations' => getDonations()
	];

	echo encodeJSON($data);
}

function pushNewDonation() {
	$data_json = $_POST['data'];
	$data = decodeJSON($data_json);
	
	pushDonation($data);
}

function pushTestDonation() {
	$item = [
		'id' => PHP_INT_MAX,
		'name' => "Test Subject",
		'amount' => rand(0, 100000) / 100,
		'currency' => "RUB",
		'message' => "This is a test alert message"
	];
	pushDonation($item);

	sleep(3);
	popDonation();
}

function resetDonations() {
	saveDonations([]);
}