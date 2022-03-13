function onload() {
	"use strict";
	const
		widget = document.getElementById("widget"),
		goalName = document.getElementById("current-bar"),
		amountText = document.getElementById("bar-text").firstChild,
		goalAmount = document.getElementById("goal-amount"),
	
	updateBar = data => {
		const currency = new Intl.NumberFormat("ru", {
			style: "currency",
			currency: data.currency
		});

		goalName.textContent = data.title;
		amountText.textContent = currency.format(data.amount);
		goalAmount.textContent = `Цель: ${currency.format(data.total)}`;
		goalName.style.width = `${data.amount / data.total * 100}%`;
	},

	getData = async () => {
		const response = await fetch('?action=update');
		return response.json();
	},

	initUpdates = async () => {
		let data = await getData();
		updateBar(data);
		
		widget.style.visibility = "visible";

		setInterval(async () => {
			data = await getData();
			updateBar(data);
		}, 3000);
	};

	initUpdates();
}

if (document.readyState === 'loading')
	document.addEventListener('DOMContentLoaded', onload);
else
	onload();