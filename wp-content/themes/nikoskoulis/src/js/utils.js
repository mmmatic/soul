// Map number x from range [a, b] to [c, d]
const map = (x, a, b, c, d) => (x - a) * (d - c) / (b - a) + c;

// Linear interpolation
const lerp = (a, b, n) => (1 - n) * a + n * b;

const clamp = (num, min, max) => num <= min ? min : num >= max ? max : num;

// Gets the mouse position
const getMousePos = (e) => {
    let posx = 0;
    let posy = 0;
    if (!e) e = window.event;
    if (e.pageX || e.pageY) {
        posx = e.pageX;
        posy = e.pageY;
    }
    else if (e.clientX || e.clientY)    {
        posx = e.clientX + body.scrollLeft + document.documentElement.scrollLeft;
        posy = e.clientY + body.scrollTop + document.documentElement.scrollTop;
    }
    
    return { x : posx, y : posy }
};

// Generate a random float.
const getRandomFloat = (min, max) => (Math.random() * (max - min) + min).toFixed(2);


const getCookie = (name) => {
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(';').shift();
	return null;
}

const setCookie = (name, value, days = 1) => {
	const expires = new Date(Date.now() + days * 864e5).toUTCString();
	document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
}


const waitForVideos = (selector = "video") => {
	const videos = Array.from(document.querySelectorAll(selector));
	if (videos.length === 0) return Promise.resolve();

	const promises = videos.map(video => {
		return new Promise(resolve => {
			if (video.readyState >= 3) {
				resolve();
			} else {
				video.addEventListener("canplaythrough", resolve, { once: true });
			}
		});
	});

	return Promise.all(promises);
}

export { map, lerp, clamp, getMousePos, getRandomFloat, getCookie, setCookie, waitForVideos };