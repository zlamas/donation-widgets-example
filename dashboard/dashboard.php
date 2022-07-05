<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<title>Donation Dashboard</title>
	<link rel="stylesheet" href="/common/common.css">
	<link rel="stylesheet" href="dashboard.css">
	<script src="dashboard.js" async></script>
</head>
<body>
<div id="dashboard">
	<div id="tabs">
		<div class="tab" id="tab-active" tab-id=0>Пожертвования</div>
		<div class="tab" tab-id=1>Панель управления</div>
	</div>
	<div id="tab-container">
		<div class="tab-content" id="tab-visible" tab-id=0>
			<table id="donation-table">
				<thead>
					<tr>
						<th>Имя</th>
						<th>Размер</th>
						<th>Дата</th>
						<th>Сообщение</th>
					</tr>
				</thead>
				<tbody id="donation-list">
				<?php
					$fmt = numfmt_create('ru_RU', NumberFormatter::CURRENCY);
					$currency = SETTINGS['currency'];
					$donations = getDonations();

					while ($donation = array_pop($donations)) {
						$amount = numfmt_format_currency($fmt, $donation['amount'], $currency);
						$date = date('d.m.Y H:i:s', $donation['date']);
				?>
					<tr>
						<td><?= $donation['username'] ?></td>
						<td><?= $amount ?></td>
						<td><?= $date ?></td>
						<td><?= $donation['message'] ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="tab-content" tab-id=1>
			<form id="donation-form" name="newDonation" method="post" action="?action=push-donation" target="null">
				<div class="form-area">
					<label class="required" for="username">Имя</label>
					<input id="username" name="username" value="Зритель" maxlength="25" required>
				</div>
				<div class="form-area">
					<label for="message">Сообщение</label>
					<textarea id="message" name="message" rows="3"></textarea>
				</div>
				<div class="form-area">
					<label class="required" for="amount">Размер</label>
					<input id="amount" name="amount" type="number" min="0.01" step="0.01" required>
					<select name="currency">
						<option value="RUB">Рубль</option>
						<option value="USD">Доллар</option>
						<option value="EUR">Евро</option>
					</select>
				</div>
				<button>Отправить</button>
			</form>
			<form target="null">
				<button id="test-alert" name="action" value="test-donation">Тестовое оповещение</button>
				<button id="reset-donations" name="action" value="reset-donations">Сбросить пожертвования</button>
			</form>
			<iframe name="null"></iframe>
		</div>
	</div>
</div>
</body>
</html>
