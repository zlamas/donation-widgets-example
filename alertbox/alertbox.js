"use strict";{
let lastChecked;
let queue = [];
let isPlaying;
const imageElem = document.querySelector("img");
const videoElem = document.querySelector("video");
const audioElem = document.querySelector("audio");
const messageNodes = document.querySelector(".alert-message").childNodes;
const userMessageElem = document.querySelector(".alert-user-message");

(async function alertsLoop() {
	const { settings, updates } =
		await (await fetch(`?action=update&from=${lastChecked}`)).json();

	lastChecked = Date.now();
	queue.push(...updates);
	if (queue.length && !isPlaying) {
		const data = queue.shift();

		messageNodes.forEach((node, i) => node.textContent = data.message[i] || "");
		userMessageElem.textContent = data.userMessage;

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

		document.body.className = "playing";
		isPlaying = true;

		setTimeout(() => {
			document.body.className = "";

			setTimeout(() => {
				isPlaying = false;
				videoElem.pause();
			}, settings.delay * 1000);
		}, settings.duration * 1000);
	}
	setTimeout(alertsLoop, settings.pollingInterval * 1000);
})();
}
