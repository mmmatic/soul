import { gsap } from 'gsap';

export default class AnimatedLinks {
        constructor(selector = ".drawer a") {
            this.links = document.querySelectorAll(selector);
            this.handleMouseEnter = this.handleMouseEnter.bind(this);
            this.handleMouseLeave = this.handleMouseLeave.bind(this);
            this.addEventListeners();
        }

        handleMouseEnter(event) {
            gsap.to(event.currentTarget, {
                skewX: -12,
                duration: 0.3,
                transformOrigin: "center",
                willChange: "transform",
                ease: "power2.out"
            });
        }

        handleMouseLeave(event) {
            if (!event.currentTarget.classList.contains('active')) {
                gsap.to(event.currentTarget, {
                    skewX: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
            }
        }

        addEventListeners() {
            this.links.forEach(link => {
                link.addEventListener("mouseenter", this.handleMouseEnter);
                link.addEventListener("mouseleave", this.handleMouseLeave);
                link.addEventListener("click", this.handleClick);

                // If parent has .current-menu-item, apply skew
                const parent = link.parentElement;
                if ((parent && parent.classList.contains("current-menu-item")) || link.classList.contains('active')) {
                    gsap.set(link, {
                        skewX: -12,
                        transformOrigin: "center",
                        willChange: "transform"
                    });
                }

                // Observe class changes on parent to remove animation if .current-menu-item is removed
                const observer = new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        if (
                            mutation.type === "attributes" &&
                            mutation.attributeName === "class"
                        ) {
                            if (link.classList.contains("active")) {
                                gsap.to(link, {
                                    skewX: -12,
                                    duration: 0.3,
                                    transformOrigin: "center",
                                    willChange: "transform",
                                    ease: "power2.out"
                                });
                            } else {
                                gsap.to(link, {
                                    skewX: 0,
                                    duration: 0.3,
                                    ease: "power2.out"
                                });
                            }
                        }
                    });
                });

                observer.observe(link, { attributes: true, attributeFilter: ["class"] });

            });
        }
    }
