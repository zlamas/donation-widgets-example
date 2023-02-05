<?php

define('TEST_DONATION_LOCK', __DIR__ . '/test_donation_file');
define('MAX_USERNAME_LENGTH', 25);
define('MAX_MESSAGE_LENGTH', 200);

require 'function.php';

if (file_exists(TEST_DONATION_LOCK)
	&& time() - filemtime(TEST_DONATION_LOCK) > SETTINGS['lockFileMaxAge'])
	unlink(TEST_DONATION_LOCK);

function getGoalBarData() {
	$data = SETTINGS['goalbar'];
	$donations = getDonations($data['startDate']);
	$amount = $data['amount'] + array_sum(array_column($donations, 'amount'));

	$data['width'] = round($amount / $data['total'] * 100, 2) . '%';
	$data['amount'] = formatCurrency($amount);
	$data['total'] = 'Цель: ' . formatCurrency($data['total']);

	unset($data['startDate']);
	echo encodeJSON($data);
}

function getAlertBoxData() {
	$settings = SETTINGS['alertbox'];
	$donations = getDonations($_GET['from']);
	$template = preg_split(
		'/({[an]})/',
		$settings['template'],
		-1,
		PREG_SPLIT_DELIM_CAPTURE
	);
	$updates = [];

	if (file_exists(TEST_DONATION_LOCK)) {
		$donations[] = [
			'amount' => rand(0, 100000) / 100,
			'currency' => SETTINGS['currency'],
			'username' => 'Test Subject',
			'message' => 'This is a test alert message'
		];
		unlink(TEST_DONATION_LOCK);
	}

	foreach ($donations as $donation) {
		$amount = formatCurrency($donation['amount'], $donation['currency']);
		$message = str_replace(
			['{a}', '{n}'],
			[$amount, $donation['username']],
			$template
		);
		$updates[] = [
			'message' => $message,
			'userMessage' => $donation['message']
		];
	}

	unset($settings['template']);
	echo encodeJSON([
		'settings' => $settings,
		'updates' => $updates
	]);
}

function saveGoalBarSettings() {
	// TODO: implement
}

function resetGoalBar() {
	// TODO: implement
}

function pushDonation($data) {
	$data['time'] = (int)(microtime(true) * 1000);
	$data['username'] = htmlspecialchars(
		substr($data['username'], 0, MAX_USERNAME_LENGTH)
	);
	$data['message'] = htmlspecialchars(
		substr($data['message'], 0, MAX_MESSAGE_LENGTH)
	);

	if ($data['currency'] != SETTINGS['currency']) {
		$data['amount'] = convertCurrency(
			$data['currency'],
			SETTINGS['currency'],
			$data['amount']
		);
		$data['currency'] = SETTINGS['currency'];
	}

	saveDonation($data);
}

function pushTestDonation() {
	touch(TEST_DONATION_LOCK);
}
