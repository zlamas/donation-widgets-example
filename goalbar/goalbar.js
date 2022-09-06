"use strict";
{
const
[ progressBar, goalName, currentAmount, goalAmount ] = document.getElementById("widget").children,

updateBar = data => {
	const currency = new Intl.NumberFormat("ru", {
		style: "currency",
		currency: data.currency
	});

	progressBar.style.width = `${data.amount / data.total * 100}%`;
	goalName.textContent = data.title;
	currentAmount.textContent = currency.format(data.amount);
	goalAmount.textContent = currency.format(data.total);
},

getData = async () => {
	const response = await fetch('?action=update');
	return response.json();
},

initUpdates = async () => {
	let data = await getData();
	updateBar(data);
	
	setInterval(async () => {
		data = await getData();
		updateBar(data);
	}, data.pollingInterval * 1000);
};

initUpdates();
}
