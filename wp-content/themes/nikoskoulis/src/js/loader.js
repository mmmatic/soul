import imagesLoaded from "imagesloaded";
import gsap from "gsap";
import SplitText from "gsap/SplitText";
import ScrollTrigger from "gsap/ScrollTrigger";
import { getCookie, setCookie, waitForVideos } from "./utils";

gsap.registerPlugin(SplitText, ScrollTrigger);

export default class Loader {
	constructor() {
		this.loader = document.getElementById("loader");
		this.progress = this.loader?.querySelector(".loader__progress");
		this.progressBar = this.loader?.querySelector(".loader__bar-fill");
		this.counterObj = { value: 0 };
	}

	init() {
		if (!this.loader) return;

		if (getCookie("loaderLoaded") === "true") {
			this.loader.style.display = "none";
			return;
		}

		document.addEventListener("DOMContentLoaded", () => {
			this.showInitialElements();
			this.animateLogoPaths();
			this.fadeInLogoName();
			this.animateTextMasking();
			this.trackAssetsLoading();
		});
	}

	async trackAssetsLoading() {
		const imgLoad = imagesLoaded(document.body, { background: true });
		const totalImages = imgLoad.images.length;
		let loadedCount = 0;

		// Videos to track
		const videos = Array.from(document.querySelectorAll("video"));
		const totalVideos = videos.length;
		let loadedVideos = 0;

		const totalAssets = totalImages + totalVideos;

		// === Progress update function ===
		const increment = () => {
			loadedCount++;
			const percent = Math.round((loadedCount / totalAssets) * 100);
			this.updateProgress(percent);
		};

		// === Image loading progress ===
		imgLoad.on("progress", increment);

		// === Video loading progress ===
		const videoPromises = videos.map(video => {
			return new Promise(resolve => {
				if (video.readyState >= 3) {
					increment(); // already loaded
					resolve();
				} else {
					video.addEventListener("canplaythrough", () => {
						increment();
						resolve();
					}, { once: true });
				}
			});
		});

		// Wait for all images and videos
		await Promise.all([
			new Promise(resolve => imgLoad.on("always", resolve)),
			Promise.all(videoPromises)
		]);

		// If something failed silently, ensure 100% shows
		this.updateProgress(100);
		this.complete();
	}
	updateProgress(percent) {
		const tl = gsap.timeline();

		if (this.counterObj.value === 0) {
			tl.set(this.progress, { autoAlpha: 0, y: 20 })
			  .to(this.progress, {
				  autoAlpha: 1,
				  y: 0,
				  duration: 0.8,
				  ease: "power2.out"
			  });
		}

		tl.to(this.counterObj, {
			value: percent,
			duration: 0.8,
			ease: "power4.out",
			onUpdate: () => {
				this.progress.textContent = `${Math.floor(this.counterObj.value)}%`;
			}
		}, "<");

		if (this.progressBar) {
			tl.to(this.progressBar, {
				scaleX: percent / 100,
				transformOrigin: "left",
				duration: 0.8,
				ease: "power4.out"
			}, "<");
		}
	}

	complete() {
		const tl = gsap.timeline();

		tl.to(this.progress, {
			autoAlpha: 0,
			y: -20,
			duration: 0.5,
			ease: "power1.inOut"
		})
		.to(this.loader, {
			autoAlpha: 0,
			pointerEvents: "none",
			duration: 0.6,
			ease: "power2.inOut",
			onComplete: () => {
				this.loader.style.display = "none";
				this.loaderLoaded();
			}
		}, "-=0.2");
	}

	loaderLoaded() {
		setCookie("loaderLoaded", "true", 3); // expires in 3 days
	}

	showInitialElements() {
		const tl = gsap.timeline();
		tl.set("#loader .loader__logo", { autoAlpha: 1 })
		  .set("#loader .loader__tagline", { autoAlpha: 1 });
	}

	animateLogoPaths() {
		const paths = document.querySelectorAll(".loader__logo path");
		const tl = gsap.timeline();

		paths.forEach((path, i) => {
			const len = path.getTotalLength();
			path.style.strokeDasharray = `0, ${len}`;
			path.style.strokeDashoffset = len;

			tl.to(path, {
				strokeDasharray: `${len}, ${len}`,
				strokeDashoffset: 0,
				duration: 2,
				ease: "power2.inOut"
			}, i * 0.1);
		});
	}

	fadeInLogoName() {
		gsap.fromTo(
			"#intro-nk-logo-name",
			{ autoAlpha: 0 },
			{
				autoAlpha: 1,
				duration: 1,
				delay: 0.3,
				ease: "power2.out",
				repeat: -1,
				yoyo: true,
			}
		);
	}

	animateTextMasking() {
		const tagline = document.querySelector(".loader__tagline");
		if (!tagline) return;

		const lines = new SplitText(tagline, { type: "lines" }).lines;

		lines.forEach(line => {
			const wrapper = document.createElement("div");
			wrapper.classList.add("wrap");
			line.parentNode.insertBefore(wrapper, line);
			wrapper.appendChild(line);
		});

		const split = new SplitText(tagline, { type: "lines" }).lines;

		split.forEach(line => {
			const wrapper = document.createElement("div");
			wrapper.classList.add("wrap");
			line.parentNode.insertBefore(wrapper, line);
			wrapper.appendChild(line);
		});

		gsap.timeline({
			scrollTrigger: {
				trigger: tagline,
				start: "top 80%",
				end: "bottom 20%",
				toggleActions: "play none none none",
			},
		})
		.from(split, {
			y: 30,
			autoAlpha: 0,
			stagger: 0.2,
			duration: 1.5,
		})
		.to(split, {
			y: "-110%",
			stagger: 0.2,
			duration: 1.5,
			delay: 6.5,
		});
	}
}
