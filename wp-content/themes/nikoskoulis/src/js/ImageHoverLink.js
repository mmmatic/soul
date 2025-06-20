import { gsap } from 'gsap';

export default class ImageHoverLink {
    constructor(el, inMenuPosition, animatableProperties) {
        this.DOM = { el: el };
        this.inMenuPosition = inMenuPosition;
        this.animatableProperties = animatableProperties;
        this.hoverImage = this.DOM.el.getAttribute('data-hover') || '';

        this.layout();
        this.initEvents();
    }

    layout() {
        this.DOM.reveal = document.createElement('div');
        this.DOM.reveal.className = 'drawer__image';
        this.DOM.reveal.style.perspective = '1000px';

        this.DOM.revealInner = document.createElement('div');
        this.DOM.revealInner.className = 'drawer__image-inner';

        // New wrapper for background image
        this.DOM.revealClip = document.createElement('div');
        this.DOM.revealClip.className = 'drawer__image-clip';

        this.DOM.revealImage = document.createElement('div');
        this.DOM.revealImage.className = 'drawer__image-img';
        this.DOM.revealImage.style.backgroundImage = `url(${this.hoverImage})`;

        // Append image to wrapper
        this.DOM.revealClip.appendChild(this.DOM.revealImage);

        // Append wrapper to inner container
        this.DOM.revealInner.appendChild(this.DOM.revealClip);
        this.DOM.reveal.appendChild(this.DOM.revealInner);

        // Append to drawer
        const drawer = document.querySelector('.drawer');
        if (drawer) {
            drawer.appendChild(this.DOM.reveal);
        }
    }
    calcBounds() {
        this.bounds = {
            el: this.DOM.el.getBoundingClientRect(),
            reveal: this.DOM.reveal.getBoundingClientRect()
        };
    }

    initEvents() {
        this.mouseenterFn = (ev) => {
            this.revealImage();
        };
        this.mouseleaveFn = () => {
            this.hideImage();
        };
        
        this.DOM.el.addEventListener('mouseenter', this.mouseenterFn);
        this.DOM.el.addEventListener('mouseleave', this.mouseleaveFn);
    }
    revealImage() {
        gsap.killTweensOf(this.DOM.revealInner);
        gsap.killTweensOf(this.DOM.revealImage);

        this.tl = gsap.timeline({
            onStart: () => {
                this.DOM.reveal.style.opacity = this.DOM.revealInner.style.opacity = 1;
                gsap.set(this.DOM.el, {zIndex: 10});
            }
        })
        .to(this.DOM.revealInner, 1.2, {
            ease: 'expo.out',
            startAt: {rotationY: -90, scale: 0.7},
            rotationY: 0,
            scale: 1
        })
        .to(this.DOM.revealClip, 1.2, {
            ease: 'expo.out',
            // Start with the same clip-path as the end value for seamlessness
            startAt: {'clipPath': 'polygon(0% 0%,100% 0,100% 100%,0% 100%)'},
            clipPath: 'polygon(0% 8%,100% 0,100% 100%,0% 92%)'
        }, 0)
        .to(this.DOM.revealImage, 1.2, {
            ease: 'expo.out',
            startAt: {scale: 1.3, filter: 'blur(8px) brightness(2)'},
            scale: 1,
            filter: 'blur(0px) brightness(1)'
        }, 0);
    }
    hideImage() {
        gsap.killTweensOf(this.DOM.revealInner);
        gsap.killTweensOf(this.DOM.revealClip);
        gsap.killTweensOf(this.DOM.revealImage);

        this.tl = gsap.timeline({
            onStart: () => {
                gsap.set(this.DOM.el, {zIndex: 1});
            },
            onComplete: () => {
                gsap.set(this.DOM.reveal, {opacity: 0});
            }
        })
        .to(this.DOM.revealInner, 0.5, {
            ease: 'expo.out',
            rotationY: 90,
            opacity: 0
        })
    }
}
