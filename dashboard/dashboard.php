<!doctype html>
<html lang="ru">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Панель управления пожертвованиями</title>
<link rel="stylesheet" href="/common/common.css">
<link rel="stylesheet" href="dashboard.css">
<div class="main">
	<input type="radio" name="tab" id="tab-donations" class="tab" checked>
	<label for="tab-donations">Пожертвования</label>
	<input type="radio" name="tab" id="tab-dashboard" class="tab">
	<label for="tab-dashboard">Управление</label>
	<div class="tab-content">
		<table class="donation-table">
			<thead>
				<tr>
					<th>Имя</th>
					<th>Размер</th>
					<th>Дата</th>
					<th>Сообщение</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach (array_reverse(getDonations()) as $donation) { ?>
				<tr>
					<td><?= $donation['username'] ?></td>
					<td><?= formatCurrency($donation['amount'], $donation['currency']) ?></td>
					<td><?= date('d.m.Y H:i:s', $donation['time'] / 1000) ?></td>
					<td><?= $donation['message'] ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="tab-content">
		<form class="donation-form" name="newDonation" method="post" action="?action=push-donation">
			<label class="required" for="donation-name">Имя</label>
			<input id="donation-name" name="username" value="Зритель" maxlength="25" required>
			<label for="donation-message">Сообщение</label>
			<textarea id="donation-message" name="message" rows="3" maxlength="200"></textarea>
			<label class="required" for="donation-amount">Размер</label>
			<input id="donation-amount" name="amount" type="number" min="0.01" step="0.01" required>
			<select name="currency">
				<option value="RUB">Рубль</option>
				<option value="USD">Доллар</option>
				<option value="EUR">Евро</option>
			</select>
			<button>Отправить</button>
		</form>
		<form class="extra-controls">
			<button name="action" value="test-donation">Тестовое оповещение</button>
			<a href="#reset-confirm">Сбросить пожертвования</a>
		</form>
	</div>
	<form id="reset-confirm" action="#">
		<div>
			Действительно сбросить все пожертвования?
			<div>
				<button name="action" value="reset-donations">Сбросить</button>
				<a href="#">Отменить</a>
			</div>
		</div>
	</form>
</div>
