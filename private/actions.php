<?php

define('SETTINGS', parse_ini_file('settings.ini', true));
define('TEST_DONATION_LOCK', __DIR__ . '/test_donation_file');

require 'function.php';

if (file_exists(TEST_DONATION_LOCK)
	&& time() > filemtime(TEST_DONATION_LOCK) + SETTINGS['lockFileMaxAge'])
	unlink(TEST_DONATION_LOCK);

function getGoalBarData() {
	$data = SETTINGS['goalbar'];
	$donations = getDonations($data['from']);
	unset($data['from']);

	$data['amount'] = array_sum(array_column($donations, 'amount'));
	$data['currency'] = SETTINGS['currency'];

	echo encodeJSON($data);
}

function getAlertBoxData() {
	$updates = getDonations($_GET['from']);

	if (file_exists(TEST_DONATION_LOCK)) {
		$updates[] = [
			'username' => 'Test Subject',
			'message' => 'This is a test alert message',
			'amount' => rand(0, 100000) / 100,
			'currency' => SETTINGS['currency']
		];
		unlink(TEST_DONATION_LOCK);
	}

	echo encodeJSON([
		'settings' => SETTINGS['alertbox'],
		'updates' => $updates
	]);
}

function pushDonation($data) {
	$data['time'] = (int)(microtime(true) * 1000);

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

function resetDonations() {
	saveDonations([]);
}
