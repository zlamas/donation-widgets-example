"use strict";{
const [ progressBar, goalName, currentAmount, goalAmount ] = document.body.firstChild.children;

(async function updateBar() {
	const data = await (await fetch('?action=update')).json();
	progressBar.style.width = data.width;
	goalName.textContent = data.title;
	currentAmount.textContent = data.amount;
	goalAmount.textContent = data.total;
	setTimeout(updateBar, data.pollingInterval * 1000);
})();
}
