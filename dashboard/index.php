<?php

require '../private/function.php';

$alertbox = get_settings('alertbox');
$goalbar = get_settings('goalbar');
$tab = $_GET['tab'];

?>
<!doctype html>
<html lang="ru">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Панель управления пожертвованиями</title>
<link rel="stylesheet" href="/css/common.css">
<link rel="stylesheet" href="dashboard.css">

<input type="radio" name="tab" id="tab-1" class="hidden-toggle" checked>
<label class="btn btn-tab" for="tab-1">Пожертвования</label>
<div class="tab">
  <table>
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

<input type="radio" name="tab" id="tab-2" class="hidden-toggle" <?php if ($tab == 2) echo 'checked' ?>>
<label class="btn btn-tab" for="tab-2">Управление</label>
<div class="tab tab-control">
  <form method="post" action="..">
    <fieldset class="control-block">
      <legend class="control-title">Добавить пожертвование</legend>
      <label for="donation-name" class="required">Имя</label>
      <input id="donation-name" class="field" name="username" value="Зритель" maxlength="25" required>
      <label for="donation-message">Сообщение</label>
      <textarea id="donation-message" class="field" name="message" rows="3" maxlength="200"></textarea>
      <label for="donation-amount" class="required">Размер</label>
      <div class="row">
        <input id="donation-amount" class="field" name="amount" type="number" inputmode="decimal" min="0.01" step="0.01" required>
        <select class="field" name="currency">
          <option value="RUB">₽ (RUB)</option>
          <option value="USD">$ (USD)</option>
          <option value="EUR">€ (EUR)</option>
        </select>
      </div>
      <div class="row form-row">
        <button class="btn btn-primary" name="action" value="push-donation">Добавить</button>
      </div>
    </fieldset>
  </form>
  <form method="post" action=".." autocomplete="off">
    <fieldset class="control-block">
      <legend class="control-title">
        <a class="widget-link" target="_blank" href="../alertbox" title="Открыть виджет в новой вкладке">Оповещения <img src="../img/link.svg" alt></a>
      </legend>
      <label for="title">Заголовок</label>
      <input id="title" class="field" name="title" maxlength="30" value="<?= $alertbox['title'] ?>">
      <div class="form-row small-text">{n} — имя донора<br>{a} — сумма пожертвования</div>
      <label for="volume">Громкость</label>
      <input id="volume" class="field" name="volume" type="range" max="1" step="0.01" value="<?= $alertbox['volume'] ?>">
      <label for="duration">Длительность</label>
      <label for="duration" class="row">
        <input id="duration" class="field" name="duration" type="number" inputmode="decimal" min="1" max="60" step="0.1" value="<?= $alertbox['duration'] ?>">
        <span>сек.</span>
      </label>
      <label for="delay">Задержка</label>
      <label for="delay" class="row">
        <input id="delay" class="field" name="delay" type="number" inputmode="decimal" min="0" max="60" step="0.1" value="<?= $alertbox['delay'] ?>">
        <span>сек.</span>
      </label>
      <div class="row form-row">
        <button class="btn btn-primary" name="action" value="alertbox-save">Сохранить</button>
        <button class="btn" name="action" value="test-donation">Тестовое оповещение</button>
      </div>
    </fieldset>
  </form>
  <form method="post" action=".." autocomplete="off">
    <fieldset class="control-block">
      <legend class="control-title">
        <a class="widget-link" target="_blank" href="../goalbar" title="Открыть виджет в новой вкладке">Полоса прогресса <img src="../img/link.svg" alt></a>
      </legend>
      <label for="goal-name" class="required">Название цели</label>
      <input id="goal-name" class="field" name="title" maxlength="30" value="<?= $goalbar['title'] ?>" required>
      <label for="start-amount">Начальный размер</label>
      <label for="start-amount" class="row">
        <input id="start-amount" class="field" name="amount" type="number" inputmode="decimal" min="0" step="0.01" value="<?= $goalbar['amount'] ?>">
        <span>₽</span>
      </label>
      <label for="goal-amount" class="required">Цель</label>
      <label for="goal-amount" class="row">
        <input id="goal-amount" class="field" name="total" type="number" inputmode="decimal" min="0.01" step="0.01" value="<?= $goalbar['total'] ?>" required>
        <span>₽</span>
      </label>
      <div class="row form-row">
        <button class="btn btn-primary" name="action" value="goalbar-save">Сохранить</button>
        <button class="btn" name="action" value="goalbar-reset">Сбросить прогресс</button>
      </div>
    </fieldset>
  </form>
  <fieldset class="control-block">
    <legend class="control-title">Другие настройки</legend>
    <div class="row form-row">
      <label class="btn btn-danger" for="dialog-toggle">Сбросить пожертвования</label>
    </div>
  </fieldset>
</div>

<input type="checkbox" id="dialog-toggle" class="hidden-toggle">
<form method="post" action=".." class="dialog">
  <fieldset class="control-block">
    <legend class="control-title">Cбросить ВСЕ пожертвования?</legend>
    <div class="row form-row">
      <button class="btn btn-danger" name="action" value="reset-donations">Сбросить</button>
      <label class="btn" for="dialog-toggle">Отменить</label>
    </div>
  </fieldset>
</form>
