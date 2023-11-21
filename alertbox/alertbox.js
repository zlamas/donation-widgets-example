(widget => {
"use strict";
let lastChecked;
let queue = [];
let isPlaying;
let imageElem = widget.querySelector("img");
let videoElem = widget.querySelector("video");
let audioElem = widget.querySelector("audio");
let messageElements = widget.querySelectorAll(".alert-message span");
let userMessage = widget.querySelector(".alert-user-message");

(function alertsLoop() {
	fetch(`?action=update&from=${lastChecked}`)
	.then(response => response.json())
	.then(data => {
		let { settings, updates } = data;

		lastChecked = Date.now();
		queue.push(...updates);
		setTimeout(alertsLoop, settings.pollingInterval * 1000);

		if (!queue.length || isPlaying)
			return;

		let alertData = queue.shift();

		messageElements.forEach((el, i) => el.textContent = alertData.message[i] || "");
		userMessage.textContent = alertData.userMessage;

		if (settings.sound) {
			audioElem.src = settings.path + settings.sound;
			audioElem.volume = settings.volume;
			audioElem.play();
		}
		if (settings.isVideo) {
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
		}, settings.duration * 1000);
	});
})();
})(document.body)
