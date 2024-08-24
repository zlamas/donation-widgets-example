<?php

define('SETTINGS', parse_ini_file('settings.ini', false, INI_SCANNER_TYPED));
define('TABLE_KEYS', ['time','username','amount','currency','message']);
define('USER_DIR', __DIR__.'/../user/');
define('USER_SETTINGS', get_settings('user'));
define('DONATIONS_FILE', USER_DIR.'donations.csv');
define('TEST_DONATION_FILE', USER_DIR.'show_test_donation');
define('MEDIA_PATH', '../user/media/');

function get_timestamp() {
    return (int)(microtime(true) * 1000);
}

function user_get_file($name) {
    return file_get_contents(USER_DIR.$name);
}

function user_save_file($name, $data) {
    file_put_contents(USER_DIR.$name, $data);
}

function from_json($data) {
    return json_decode($data, true) ?? [];
}

function to_json($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function get_settings($name) {
    return from_json(user_get_file($name.'.json'));
}

function save_settings($name, $settings) {
    user_save_file($name.'.json', to_json($settings));
}

function get_donations($from = -1) {
    $file = fopen(DONATIONS_FILE, 'r');
    while (($data = fgetcsv($file, 1000)) !== false) {
        if ($data[0] > $from) {
            $donations[] = array_combine(TABLE_KEYS, $data);
        }
    }
    fclose($file);
    return $donations ?? [];
}

function save_donation($donation) {
    foreach (TABLE_KEYS as $key) {
        $line[] = $donation[$key];
    }
    $file = fopen(DONATIONS_FILE, 'a');
    fputcsv($file, $line);
    fclose($file);
}

function format_currency($amount, $currency = USER_SETTINGS['currency']) {
    return numfmt_format_currency(
        numfmt_create('ru_RU', NumberFormatter::CURRENCY),
        $amount,
        $currency
    );
}

function get_currency_rates() {
    // TODO: get live currency rates
    return [
        'RUB' => 1,
        'USD' => 80,
        'EUR' => 90
    ];
}

function convert_currency($from, $to, $amount) {
    if ($from === $to) {
        return $amount;
    }
    $rates = get_currency_rates();
    if ($rates[$from] && $rates[$to]) {
        return round($rates[$from] / $rates[$to] * $amount, 2);
    }
    return 0;
}
