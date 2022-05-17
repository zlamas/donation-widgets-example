(() => {
	"use strict";
	const
	tabContainer = document.getElementById("tab-container"),
	donationList = document.getElementById("donation-list"),
	tabs = document.getElementsByClassName("tab"),
	tabContents = document.getElementsByClassName("tab-content"),

	renderDonation = data => {
		const
		currency = new Intl.NumberFormat("ru", {
			style: "currency",
			currency: data.currency
		}),
		date = new Date().toLocaleString().replace(",", ""),
		id = +donationList.rows[0].getAttribute("donation-id") + 1,
		row = donationList.insertRow(0);

		row.setAttribute('donation-id', id);

		row.insertCell().textContent = data.username;
		row.insertCell().textContent = currency.format(data.amount);
		row.insertCell().textContent = date;
		row.insertCell().textContent = data.message;
	};

	document.addEventListener('submit', e => {
		const form = e.target;

		if (form.name == 'newDonation') {
			const formData = new FormData(form);
			
			renderDonation(Object.fromEntries(formData));
			
			tabs[0].click();
			tabContainer.scrollTop = 0;

			form.submit();
			form.reset();
			e.preventDefault();
		}
	});

	document.getElementById("reset-donations")
		.addEventListener("click", function(e) {
			if (confirm("Cбросить сохранённые пожертвования?"))
				donationList.innerHTML = "";
			else
				e.preventDefault();
	});

	Array.prototype.forEach.call(tabs,
		elem => elem.addEventListener("click", function() {
			document.getElementById("tab-active").id = "";
			this.id = "tab-active";

			document.getElementById("tab-visible").id = "";
			tabContents[this.getAttribute("tab-id")].id = "tab-visible";
		})
	);

	// ios specific fixes
	if ("standalone" in navigator) {
		// prevent double-tap to zoom
		tabContainer.addEventListener("click", () => {});

		// fix scrolling bug
		tabContainer.addEventListener("touchstart", function(e) {
			this.atTop = (this.scrollTop <= 0);
			this.atBottom = (this.scrollTop >= this.scrollHeight - this.clientHeight);
			this.lastY = e.touches[0].clientY;
		});
		tabContainer.addEventListener("touchmove", function(e) {
			const up = (e.touches[0].clientY > this.lastY);

			this.lastY = e.touches[0].clientY;

			if ((up && this.atTop) || (!up && this.atBottom))
				e.preventDefault();
		});
	}
})();
