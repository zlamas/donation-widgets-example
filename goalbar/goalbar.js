(widget => {
"use strict";
let progressBar = widget.querySelector(".progress-bar");
let goalName = widget.querySelector(".goal-name");
let currentAmount = widget.querySelector(".current-amount");
let goalAmount = widget.querySelector(".goal-amount");

(function updateBar() {
	fetch('?action=update')
	.then(response => response.json())
	.then(data => {
		progressBar.style.width = data.percentage;
		goalName.textContent = data.title;
		currentAmount.textContent = data.amount;
		goalAmount.textContent = data.total;
		setTimeout(updateBar, data.pollingInterval * 1000);
	});
})();
})(document.body)
