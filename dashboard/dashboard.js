function onload() {
	let lastDonationId = -1,
		activeCurrency;

	document.addEventListener('submit', e => {
		const form = e.target;

		if (!form)
			return;
		
		if (form.name == 'newDonation') {
			const
			formElems = form.elements,
			data = {
				id: ++lastDonationId,
				date: Math.floor(Date.now() / 1000),
				name: formElems.username.value,
				amount: convertCurrency(
					formElems.currency.value,
					activeCurrency,
					+formElems.amount.value
				),
				currency: activeCurrency,
			};
			
			if (formElems.message.value)
				data.message = formElems.message.value;

			renderDonation(data);
			pushDonation(data);

			tabs[0].click();
			tableContainer.scrollTop = 0;
		}

		e.preventDefault();

		form.reset();
	});

	const
	tableContainer = document.getElementById("table-container"),
	donationList = document.getElementById("donation-list"),
	newDonationTab = document.getElementById("new-donation"),
	tabs = document.getElementsByClassName("tab"),
	rates = {
		'RUB': 1,
		'USD': 116.75,
		'EUR': 128.95
	},

	initDashboard = async () => {
		const 
		{ settings, donations } = await getData(),
		{ alertbox, goalbar, currency } = settings;

		activeCurrency = currency;
		// initAlertBoxSettings(alertbox);
		// initGoalBarSettings(goalbar);
		
		if (donations.length) {
			donations.forEach(item => renderDonation(item));
			lastDonationId = donations.pop().id;
		}
	},

	// initAlertBoxSettings = settings => {

	// },

	// initGoalBarSettings = settings => {

	// },

	convertCurrency = (from, to, amount) => {
		if (from === to)
			return amount;

		if (rates[from] && rates[to])
			return rates[from] / rates[to] * amount;
		
		return 0;
	}

	pushDonation = async data => {
		const formData = new FormData;

		formData.append('data', JSON.stringify(data));
		
		await fetch('?action=push-donation', {
			method: 'POST',
			body: formData
		});
	},
	
	showTestDonation = async () =>
		await fetch('?action=test-donation'),

	resetDonations = async () => {
		donationList.innerHTML = "";
		await fetch('?action=reset-donations');
	},

	getData = async () => {
		const response = await fetch('?action=init');
		return response.json();
	},

	renderDonation = data => {
		const
		row = donationList.insertRow(0),
		currency = new Intl.NumberFormat("ru", {
			style: "currency",
			currency: activeCurrency
		}),
		amount = convertCurrency(data.currency, activeCurrency, data.amount),
		date = new Date(data.date * 1000).toLocaleString();

		row.setAttribute('donation-id', data.id);

		row.insertCell().textContent = data.name;
		row.insertCell().textContent = currency.format(amount);
		row.insertCell().textContent = date.replace(",", "");
		row.insertCell().textContent = data.message;
	};

	document.getElementById("test-alert").onclick = showTestDonation;
		
	document.getElementById("reset-donations").onclick = function() { 
		if (confirm("Cбросить сохранённые пожертвования?"))
			resetDonations();
	};

	tabs[0].onclick = function() {
		tabs[1].classList.remove("active");
		this.classList.add("active");
		tableContainer.style.display = "";
		newDonationTab.style.display = "none";
	};

	tabs[1].onclick = function() {
		tabs[0].classList.remove("active");
		this.classList.add("active");
		tableContainer.style.display = "none";
		newDonationTab.style.display = "";
	};

	tabs[0].click();

	// fix scrolling bug on ios (thanks apple)
	tableContainer.ontouchstart = function(e) {
		this.allowUp = (this.scrollTop > 0);
		this.allowDown = (this.scrollTop < this.scrollHeight - this.clientHeight);
		this.prevTop = null;
		this.prevBot = null;
		this.lastY = e.targetTouches[0].pageY;
	};
	tableContainer.ontouchmove = function(e) {
		const up = (e.targetTouches[0].pageY > this.lastY);

		this.lastY = e.targetTouches[0].pageY;

		if ((up && this.allowUp) || (!up && this.allowDown))
			e.stopPropagation();
		else
			e.preventDefault();
	};
	
	initDashboard();
}

if (document.readyState === 'loading')
	document.addEventListener('DOMContentLoaded', onload);
else
	onload();