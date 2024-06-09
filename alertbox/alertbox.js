(() => {
'use strict';
let widget = document.querySelector('.alertbox');
let imageElem = widget.querySelector('img');
let videoElem = widget.querySelector('video');
let audioElem = widget.querySelector('audio');
let titleElems = widget.querySelectorAll('.alert-title span');
let userMessage = widget.querySelector('.alert-message');
let queue = [];
let lastChecked;
let isPlaying;

(function alertsLoop() {
	fetch(`../?action=alertbox-update&from=${lastChecked}`)
	.then((response) => response.json())
	.then((data) => {
		lastChecked = Date.now();
		setTimeout(alertsLoop, data.pollingInterval * 1000);

		if (data.alerts) queue.push(...data.alerts);
		if (!queue.length || isPlaying) return;

		let { title, message } = queue.shift();
		titleElems.forEach((el, i) => el.textContent = title[i] || '');
		userMessage.textContent = message;

		if (data.sound) {
			audioElem.src = data.path + data.sound;
			audioElem.volume = data.volume;
			audioElem.play();
		}

		if (data.isVideo) {
			videoElem.src = data.path + data.image;
			videoElem.style.display = '';
			imageElem.style.display = 'none';
			videoElem.muted = data.sound;
			videoElem.play();
		} else {
			imageElem.src = data.path + data.image;
			videoElem.style.display = 'none';
			imageElem.style.display = '';
		}

		widget.classList.add('playing');
		isPlaying = true;

		setTimeout(() => {
			widget.classList.remove('playing');
			setTimeout(() => {
				isPlaying = false;
				videoElem.pause();
			}, data.delay * 1000);
		}, data.duration * 1000);
	})
	.catch((error) => {
		console.error(error);
		setTimeout(alertsLoop, 15000);
	});
})();
})();
