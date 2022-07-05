<?php

define('DONATIONS_FILE', __DIR__ . '/donations.csv');
define('TABLE_KEYS', ['id','date','username','amount','currency','message']);

function encodeJSON($data) {
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function getDonations($from=-1) {
	$file = fopen(DONATIONS_FILE, 'r');
	$donations = [];

	while (($data = fgetcsv($file, 1000)) !== FALSE)
		if ($data[0] > $from)
			$donations[] = array_combine(TABLE_KEYS, $data);

	fclose($file);
	return $donations;
}

function saveDonation($donation) {
	$line = [];
	foreach (TABLE_KEYS as $key)
		$line[] = $donation[$key];

	$file = fopen(DONATIONS_FILE, 'a');
	fputcsv($file, $line);
	fclose($file);
}

function saveDonations($donations) {
	file_put_contents(DONATIONS_FILE, $donations);
}

function getLastDonationId() {
	$file = fopen(DONATIONS_FILE, "r");

	fseek($file, 0, SEEK_END);
	if (!ftell($file)) {
		fclose($file);
		return -1;
	}

	$buffer = 64;
	$tail = '';

	while (ftell($file) > 0 && substr_count($tail, "\n") < 2) {
		$seek = min(ftell($file), $buffer);
		fseek($file, -$seek, SEEK_CUR);
		$tail = fread($file, $seek);
		fseek($file, -$seek, SEEK_CUR);
	}

	while (substr_count($tail, "\n") > 1)
		$tail = substr($tail, strpos($tail, "\n") + 1);

	fclose($file);

	$id = explode(',', $tail)[0];
	return $id ?? -1;
}

function popDonation() {
	$donations = file(DONATIONS_FILE);
	array_pop($donations);
	saveDonations($donations);
}

function convertCurrency($from, $to, $amount) {
	if ($from === $to)
		return $amount;

	// $rates = getCurrencyRates();
	$rates = [
		'RUB' => 1,
		'USD' => 67.95,
		'EUR' => 72.34
	];

	if ($rates[$from] && $rates[$to])
		return round($rates[$from] / $rates[$to] * $amount, 2);

	return 0;
}
