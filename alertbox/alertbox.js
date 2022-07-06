"use strict";
{
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
			`<span class=highlight>${data.username}</span>`)
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
	
	widget.className = "playing";
	isPlaying = true;

	setTimeout(() => {
		widget.className = "";

		setTimeout(() => {
			isPlaying = false;
			videoElem.pause();
		}, settings.delay * 1000);
	}, settings.time * 1000);
},

getUpdates = async () => {
	const response =
		await fetch(`?action=update&from=${lastDonationId}`);
	return response.json();
},

initUpdates = async () => {
	let { settings, updates } = await getUpdates();
	lastDonationId = settings.id;

	setInterval(async () => {
		({ settings, updates } = await getUpdates());
		lastDonationId = settings.id;
		queue.push(...updates);

		if (queue.length && !isPlaying)
			displayAlert(queue.shift(), settings);
	}, settings.pollingInterval * 1000);
};

initUpdates();
}
