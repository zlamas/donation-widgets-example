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

function encodeJSON($json) {
	return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function getAndDecodeJSON($path) {
	return decodeJSON(getFile($path));
}

function encodeAndPutJSON($path, $json) {
	file_put_contents(__DIR__ . $path, encodeJSON($json));
}

function getDonations() {
	return getAndDecodeJSON('/donations.json');
}

function filterById($array, $fromId) {
	$filter = array_filter($array, function ($item) use ($fromId) {
		return $item['id'] > $fromId;
	});

	return array_values($filter);
}

function saveDonations($donations) {
	encodeAndPutJSON('/donations.json', $donations);
}

function pushDonation($data) {
	$donations = getDonations();
	array_push($donations, $data);
	saveDonations($donations);
}

function popDonation() {
	$donations = getDonations();
	array_pop($donations);
	saveDonations($donations);
}

function getSettings() {
	return getAndDecodeJSON('/settings.json');
}