function onload() {
	"use strict";
	let lastDonationId = -1,
		queue = [],
		isPlaying = false;

	const
		widget = document.getElementById("widget"),
		imageElem = document.getElementById("alert-image"),
		videoElem = document.getElementById("alert-video"),
		audioElem = document.getElementById("alert-sound"),
		messageElem = document.getElementById("alert-message"),
		userMsgElem = document.getElementById("alert-user-message"),
	
	displayAlert = (data, settings) => {
		const
			currency = new Intl.NumberFormat("ru", {
				style: "currency",
				currency: data.currency
			});

		messageElem.innerHTML = settings.template
			.replace("{name}",
				`<span class=highlight>${data.name}</span>`)
			.replace("{amount}",
				`<span class=highlight>${currency.format(data.amount)}</span>`);
		userMsgElem.textContent = data.message;

		if (settings.sound) {
			audioElem.src = settings.path + settings.sound;
			audioElem.volume = settings.volume;
			audioElem.play();
		}

		if (settings.imageType == "video") {
			videoElem.src = settings.path + settings.image;
			videoElem.style.display = "";
			imageElem.style.display = "none";
			videoElem.muted = settings.sound;
			videoElem.play();
		} else {
			imageElem.src = settings.path + settings.image;
			videoElem.style.display = "none";
			imageElem.style.display = "";
		}
		
		widget.style.visibility = "visible";
		widget.className = "playing";
		isPlaying = true;

		setTimeout(() => {
			widget.className = "";
			videoElem.pause();
			
			setTimeout(() => {
				isPlaying = false;
				widget.style.visibility = "";
			}, settings.delay * 1000);
		}, settings.time * 1000);
	},

	getUpdates = async () => {
		const response = 
			await fetch(`?action=update&from=${lastDonationId}`);
		return response.json();
	},

	initUpdates = async () => {
		let data = await getUpdates();
		lastDonationId = data.settings.id;

		setInterval(async () => {
			data = await getUpdates();
			lastDonationId = data.settings.id;

			queue.push(...data.updates);
			
			if (!queue.length)
				return;
			
			if (!isPlaying) {
				const next = queue.shift();
				displayAlert(next, data.settings);
			}
		}, 3000);
	};

	videoElem.loop = true;

	initUpdates();
}

if (document.readyState === 'loading')
	document.addEventListener('DOMContentLoaded', onload);
else
	onload();