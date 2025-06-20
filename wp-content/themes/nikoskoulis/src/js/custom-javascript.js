import Lenis from 'lenis';

import { gsap } from "gsap";

import { ScrollTrigger } from "gsap/ScrollTrigger";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";

// components
import Loader from "./loader";
import Drawer from './drawer';
import HorizontalGrid from './horizontal-grid';
import AnimatedLinks from './animatedLinks';

gsap.registerPlugin(ScrollTrigger,ScrollToPlugin);

// Initialize loader
const loader = new Loader();
loader.init();

// Initialize Lenis smooth scrolling

const lenis = new Lenis({
});

gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
});

gsap.ticker.lagSmoothing(0);

document.addEventListener("DOMContentLoaded", () => {

    // Animate anchors on hover
    new AnimatedLinks();
    
    // initialize drawer enu
    new Drawer({
        drawerSelector: '#drawer',
        buttonSelector: '#drawerToggle',
    });

    if (document.body.classList.contains('home')) {
        new HorizontalGrid()
    }


});
