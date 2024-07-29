<?php

require 'function.php';

function update_alertbox() {
    $data = get_settings('alertbox');
    $donations = get_donations($_GET['from']);

    if (file_exists(TEST_DONATION_FILE)
    &&  time() - filemtime(TEST_DONATION_FILE) < SETTINGS['polling_interval']) {
        $donations[] = [
            'amount' => rand(0, 100000) / 100,
            'currency' => USER_SETTINGS['currency'],
            'username' => 'Test Subject',
            'message' => 'This is a test alert message'
        ];
        unlink(TEST_DONATION_FILE);
    }
    if (!empty($donations)) {
        $template = preg_split(
            '/({[an]})/',
            $data['title'],
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );
    }
    foreach ($donations as $donation) {
        $amount = format_currency($donation['amount'], $donation['currency']);
        $title = str_replace(
            ['{a}', '{n}'],
            [$amount, $donation['username']],
            $template
        );
        $alerts[] = [
            'title' => $title,
            'message' => $donation['message']
        ];
    }

    unset($data['title']);
    $data['path'] = MEDIA_PATH;
    $data['pollingInterval'] = SETTINGS['polling_interval'];
    if (!empty($alerts)) {
        $data['alerts'] = $alerts;
    }
    echo to_json($data);
}

function update_goalbar() {
    $data = get_settings('goalbar');
    $donations = get_donations($data['startTime']);
    $amount = $data['amount'] + array_sum(array_column($donations, 'amount'));

    unset($data['startTime']);
    $data['percentage'] = round($amount / $data['total'] * 100, 2).'%';
    $data['amount'] = format_currency($amount);
    $data['total'] = 'Цель: '.format_currency($data['total']);
    $data['pollingInterval'] = SETTINGS['polling_interval'];

    echo to_json($data);
}

function save_alertbox_settings($settings) {
    $settings = array_merge(get_settings('alertbox'), $settings);
    save_settings('alertbox', $settings);
}

function save_goalbar_settings($settings) {
    $settings = array_merge(get_settings('goalbar'), $settings);
    save_settings('goalbar', $settings);
}

function reset_goalbar() {
    $settings = get_settings('goalbar');
    $settings['startTime'] = get_timestamp();
    save_settings('goalbar', $settings);
}

function push_donation($data) {
    $data['time'] = get_timestamp();
    $data['username'] = htmlspecialchars(
        substr($data['username'], 0, SETTINGS['max_username_length'])
    );
    $data['message'] = htmlspecialchars(
        substr($data['message'], 0, SETTINGS['max_message_length'])
    );
    if ($data['currency'] != USER_SETTINGS['currency']) {
        $data['amount'] = convert_currency(
            $data['currency'],
            USER_SETTINGS['currency'],
            $data['amount']
        );
        $data['currency'] = USER_SETTINGS['currency'];
    }
    save_donation($data);
}

function push_test_donation() {
    touch(TEST_DONATION_FILE);
}

function reset_donations() {
    file_put_contents(DONATIONS_FILE, "");
}
