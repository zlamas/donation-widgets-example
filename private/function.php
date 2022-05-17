<?php

function getFile($path) {
	return file_get_contents(__DIR__ . $path);
}

function decodeJSON($json) {
	$data = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE);
	if (empty($data))
		return [];
	return $data;
}

function getAndDecodeJSON($path) {
	return decodeJSON(getFile($path));
}

function encodeJSON($data) {
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function encodeAndPutJSON($data, $path) {
	file_put_contents(__DIR__ . $path, encodeJSON($data));
}

function getDonations() {
	return getAndDecodeJSON('/donations.json');
}

function saveDonations($donations) {
	encodeAndPutJSON($donations, '/donations.json');
}

function getSettings() {
	return getAndDecodeJSON('/settings.json');
}

function filterById($array, $fromId) {
	$filter = array_filter($array, function ($item) use ($fromId) {
		return $item['id'] > $fromId;
	});

	return array_values($filter);
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
