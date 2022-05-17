<?php

require 'function.php';

$settings = getSettings();

function getGoalBarData() {
	global $settings;

	$data = $settings['goalbar'];
	$donations = filterById(getDonations(), $data['from']);

	$data['amount'] = array_sum(array_column($donations, 'amount'));
	$data['currency'] = $settings['currency'];

	echo encodeJSON($data);
}

function getAlertBoxData() {
	global $settings;

	$alertboxSettings = $settings['alertbox'];
	$fromId = $_GET['from'];
	$donations = getDonations();

	if ($donations) {
		$updates = filterById($donations, $fromId);
		$alertboxSettings['id'] = end($updates)['id'] ?? end($donations)['id'];
	} else {
		$updates = [];
		$alertboxSettings['id'] = -1;
	}

	$data = [
		'settings' => $alertboxSettings,
		'updates' => $updates
	];

	echo encodeJSON($data);
}

function pushDonation($data) {
	global $settings;

	$donations = getDonations();
	
	if ($donations)
		$data['id'] = end($donations)['id'] + 1;
	else
		$data['id'] = 0;
	
	$data['date'] = time();

	if ($data['currency'] != $settings['currency']) {
		$data['amount'] = convertCurrency(
			$data['currency'],
			$settings['currency'],
			$data['amount']
		);

		$data['currency'] = $settings['currency'];
	}

	array_push($donations, $data);
	saveDonations($donations);
}

function popDonation() {
	$donations = getDonations();
	array_pop($donations);
	saveDonations($donations);
}

function pushTestDonation() {
	global $settings;

	$item = [
		'username' => "Test Subject",
		'message' => "This is a test alert message",
		'amount' => rand(0, 100000) / 100,
		'currency' => $settings['currency']
	];
	pushDonation($item);

	sleep($settings['goalbar']['pollingInterval']);
	popDonation();
}

function resetDonations() {
	saveDonations([]);
}
