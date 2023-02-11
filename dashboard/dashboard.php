<!doctype html>
<html lang="ru">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Панель управления пожертвованиями</title>
<link rel="stylesheet" href="/common/common.css">
<link rel="stylesheet" href="dashboard.css">

<input type="radio" name="tab" id="tab-1" class="tab" checked>
<label class="tab-label" for="tab-1">Пожертвования</label>
<input type="radio" name="tab" id="tab-2" class="tab">
<label class="tab-label" for="tab-2">Управление</label>
<div id="tab-content-1" class="tab-content">
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
<div id="tab-content-2" class="tab-content">
	<form method="post" action="?action=push-donation">
		<fieldset>
			<legend>Создать пожертвование</legend>
			<label for="donation-name" class="required">Имя</label>
			<input id="donation-name" name="username" value="Зритель" maxlength="25" required>
			<label for="donation-message">Сообщение</label>
			<textarea id="donation-message" name="message" rows="3" maxlength="200"></textarea>
			<label for="donation-amount" class="required">Размер</label>
			<div class="input-row">
				<input id="donation-amount" name="amount" type="number" min="0.01" step="0.01" required>
				<select name="currency">
					<option value="RUB">₽ (RUB)</option>
					<option value="USD">$ (USD)</option>
					<option value="EUR">€ (EUR)</option>
				</select>
			</div>
			<div class="button-row">
				<button>Отправить</button>
			</div>
		</fieldset>
	</form>
	<form method="post">
		<fieldset>
			<legend>Виджет прогресса</legend>
			<label for="goal-name" class="required">Название цели</label>
			<input id="goal-name" name="goalname" maxlength="30" value="<?= SETTINGS['goalbar']['title'] ?>" required>
			<label for="starting-amount">Начальный размер</label>
			<div class="input-row">
				<input id="starting-amount" name="amount" type="number" min="0" step="0.01" value="<?= SETTINGS['goalbar']['amount'] ?>">
				<label>₽</label>
			</div>
			<label for="goal-amount" class="required">Цель</label>
			<div class="input-row">
				<input id="goal-amount" name="total" type="number" min="0.01" step="0.01" value="<?= SETTINGS['goalbar']['total'] ?>" required>
				<label>₽</label>
			</div>
			<div class="button-row">
				<button formaction="?action=goalbar-save">Сохранить</button>
				<button formaction="?action=goalbar-reset">Сбросить</button>
			</div>
		</fieldset>
	</form>
	<form>
		<fieldset>
			<legend>Другие настройки</legend>
			<div class="button-row">
				<button name="action" value="test-donation">Тестовое оповещение</button>
			</div>
			<div class="button-row">
				<a href="#reset-confirm">Сбросить пожертвования</a>
			</div>
		</fieldset>
	</form>
</div>
<form id="reset-confirm">
	<fieldset>
		<legend>Cбросить ВСЕ пожертвования?</legend>
		<div class="button-row">
			<button name="action" value="reset-donations">Сбросить</button>
			<a href="#">Отменить</a>
		</div>
	</fieldset>
</form>
