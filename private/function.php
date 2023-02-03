<?php

define('SETTINGS', parse_ini_file('settings.ini', true));
define('DONATIONS_FILE', __DIR__ . '/donations.csv');
define('TABLE_KEYS', ['time','username','amount','currency','message']);

function encodeJSON($data) {
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function getDonations($from = -1) {
	$file = fopen(DONATIONS_FILE, 'r');
	$donations = [];

	while (($data = fgetcsv($file, 1000)) !== false)
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

function resetDonations() {
	file_put_contents(DONATIONS_FILE, "");
}

function formatCurrency($amount, $currency = SETTINGS['currency']) {
	return numfmt_format_currency(
		numfmt_create('ru_RU', NumberFormatter::CURRENCY),
		$amount,
		$currency
	);
}

function getCurrencyRates() {
	// TODO: get live currency rates
	return [
		'RUB' => 1,
		'USD' => 70.04,
		'EUR' => 76.96
	];
}

function convertCurrency($from, $to, $amount) {
	if ($from === $to)
		return $amount;

	$rates = getCurrencyRates();
	if ($rates[$from] && $rates[$to])
		return round($rates[$from] / $rates[$to] * $amount, 2);

	return 0;
}
