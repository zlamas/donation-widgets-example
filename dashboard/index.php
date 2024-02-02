<?php

require '../private/function.php';

$alertbox = get_settings('alertbox');
$goalbar = get_settings('goalbar');

?>
<!doctype html>
<html lang="ru">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Панель управления пожертвованиями</title>
<link rel="stylesheet" href="/css/common.css">
<link rel="stylesheet" href="dashboard.css">

<input type="radio" name="tab" id="tab-1" class="tab-radio" checked>
<label class="tab-label" for="tab-1">Пожертвования</label>
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
<?php foreach (array_reverse(get_donations()) as $donation) { ?>
		<tr>
			<td><?= $donation['username'] ?></td>
			<td><?= format_currency($donation['amount'], $donation['currency']) ?></td>
			<td><?= date('d.m.Y H:i:s', (int)($donation['time'] / 1000)) ?></td>
			<td><?= $donation['message'] ?></td>
		</tr>
<?php } ?>
	</table>
</div>

<input type="radio" name="tab" id="tab-2" class="tab-radio">
<label class="tab-label" for="tab-2">Управление</label>
<div id="tab-content-2" class="tab-content tab-control-panel">
	<form method="post" action="..">
		<fieldset class="control-block">
			<legend class="control-title">Добавить пожертвование</legend>
			<label for="donation-name" class="required">Имя</label>
			<input id="donation-name" name="username" value="Зритель" maxlength="25" required>
			<label for="donation-message">Сообщение</label>
			<textarea id="donation-message" name="message" rows="3" maxlength="200"></textarea>
			<label for="donation-amount" class="required">Размер</label>
			<div class="input-field">
				<input id="donation-amount" name="amount" type="number" min="0.01" step="0.01" required>
				<select name="currency">
					<option value="RUB">₽ (RUB)</option>
					<option value="USD">$ (USD)</option>
					<option value="EUR">€ (EUR)</option>
				</select>
			</div>
			<div class="form-row">
				<button class="button" name="action" value="push-donation">Отправить</button>
			</div>
		</fieldset>
	</form>
	<form method="post" action=".." autocomplete="off">
		<fieldset class="control-block">
			<legend class="control-title">
				<a class="widget-link" target="_blank" href="../alertbox" title="Открыть виджет в новой вкладке">Оповещения <img src="../img/link.svg" alt></a>
			</legend>
			<label for="title">Заголовок</label>
			<input id="title" name="title" maxlength="30" value="<?= $alertbox['title'] ?>">
			<div class="form-row small-text">{n} — имя донора<br>{a} — сумма пожертвования</div>
			<label for="volume">Громкость</label>
			<input id="volume" name="volume" type="range" max="1" step="0.01" value="<?= $alertbox['volume'] ?>">
			<label for="duration">Длительность</label>
			<div class="input-field">
				<input id="duration" name="duration" type="number" min="1" max="60" step="0.5" value="<?= $alertbox['duration'] ?>">
				<label>сек.</label>
			</div>
			<label for="delay">Задержка</label>
			<div class="input-field">
				<input id="delay" name="delay" type="number" min="0" max="60" step="0.5" value="<?= $alertbox['delay'] ?>">
				<label>сек.</label>
			</div>
			<div class="form-row">
				<button class="button" name="action" value="alertbox-save">Сохранить</button>
			</div>
		</fieldset>
	</form>
	<form method="post" action=".." autocomplete="off">
		<fieldset class="control-block">
			<legend class="control-title">
				<a class="widget-link" target="_blank" href="../goalbar" title="Открыть виджет в новой вкладке">Полоса прогресса <img src="../img/link.svg" alt></a>
			</legend>
			<label for="goal-name" class="required">Название цели</label>
			<input id="goal-name" name="title" maxlength="30" value="<?= $goalbar['title'] ?>" required>
			<label for="start-amount">Начальный размер</label>
			<div class="input-field">
				<input id="start-amount" name="amount" type="number" min="0" step="0.01" value="<?= $goalbar['amount'] ?>">
				<label>₽</label>
			</div>
			<label for="goal-amount" class="required">Цель</label>
			<div class="input-field">
				<input id="goal-amount" name="total" type="number" min="0.01" step="0.01" value="<?= $goalbar['total'] ?>" required>
				<label>₽</label>
			</div>
			<div class="form-row">
				<button class="button" name="action" value="goalbar-save">Сохранить</button>
				<button class="button" name="action" value="goalbar-reset">Сбросить прогресс</button>
			</div>
		</fieldset>
	</form>
	<form method="post" action="..">
		<fieldset class="control-block">
			<legend class="control-title">Другие настройки</legend>
			<div class="form-row">
				<button class="button" name="action" value="test-donation">Тестовое оповещение</button>
			</div>
			<div class="form-row">
				<a class="button button-red" href="#reset-confirm">Сбросить пожертвования</a>
			</div>
		</fieldset>
	</form>
</div>
<form method="post" action=".." id="reset-confirm" class="reset-confirm">
	<fieldset class="control-block">
		<legend class="control-title">Cбросить ВСЕ пожертвования?</legend>
		<div class="form-row">
			<button class="button button-red" name="action" value="reset-donations">Сбросить</button>
			<a class="button" href="#">Отменить</a>
		</div>
	</fieldset>
</form>
