"use strict";{
const
[ progressBar, goalName, currentAmount, goalAmount ] = document.getElementById("widget").children;

(async function updateBar() {
	const
	data = await (await fetch('?action=update')).json(),
	currency = new Intl.NumberFormat("ru", {
		style: "currency",
		currency: data.currency
	});

	progressBar.style.width = `${data.amount / data.total * 100}%`;
	goalName.textContent = data.title;
	currentAmount.textContent = currency.format(data.amount);
	goalAmount.textContent = currency.format(data.total);
	setTimeout(updateBar, data.pollingInterval * 1000);
})();
}
