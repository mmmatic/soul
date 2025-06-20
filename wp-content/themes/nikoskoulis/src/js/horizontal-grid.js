import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import VanillaTilt from 'vanilla-tilt';

gsap.registerPlugin(ScrollTrigger);

export default class HomepageGrid {
    constructor() {
      this.el = document.querySelector('.homepage__grid');
      this.scrollAnimation = null;
      this.sectionsWidth = 0;
      this.anchors = this.el.querySelectorAll('a.card');
      this.card_titles = document.querySelectorAll('.card__title');

      this.init()
    }

    init() {
      if (!this.el) return;

      ScrollTrigger.config({ debounce: true, throttle: 150 });

      this.initTiltEffects();
      this.initTitleHover();
      this.setupGrid();
      this.animateGrid();
    }

    initTitleHover() {
      
      this.anchors.forEach(anchor => {
        const anchorReveal = anchor.querySelector('.card__title-text');
        gsap.set(anchorReveal, { yPercent: 25, autoAlpha: 0 });
        if (!anchorReveal) return;

        anchor.addEventListener('mouseenter', () => {
          gsap.to(anchorReveal, {
            yPercent: 0,
            autoAlpha: 1,
            duration: 0.3,
            ease: 'power2.out',
          });
        });

        anchor.addEventListener('mouseleave', () => {
          gsap.to(anchorReveal, {
            yPercent: 100,
            autoAlpha: 0,
            duration: 0.3,
            ease: 'power2.in',
          });
        });
      });
    }

    initTiltEffects() {
      if (window.innerWidth <= 1024) return;

      const tiltElements = document.querySelectorAll('.card--tilted');
      VanillaTilt.init(tiltElements, {
        reverse: true,
        max: 10,
        speed: 3000,
        scale: 1.05,
        reset: true,
        perspective: 1500,
        transition: true,
        glare: true,
        'max-glare': 0.45,
        'glare-prerender': false,
        gyroscope: true,
        gyroscopeMinAngleX: -15,
        gyroscopeMaxAngleX: 15,
        gyroscopeMinAngleY: -15,
        gyroscopeMaxAngleY: 15,
      });
    }

    setupGrid() {

      const wrapper = document.querySelector('.homepage__grid-container');

      gsap.to(wrapper, {
      x: () => -(wrapper.scrollWidth - innerWidth) + 'px',
      ease: 'none',
      scrollTrigger: {
          trigger: '.homepage__grid',
          start: 'top top',
          end: () => `+=${wrapper.scrollWidth - window.innerWidth}`,
          pin: true,
          scrub: true,
          anticipatePin: 1,
      },
      });
    }

  animateGrid() {
      const gridItems = gsap.utils.toArray('.homepage__grid-item');
      const itemSpeed = gsap.utils.toArray("[data-speed]");

      gridItems.forEach(item => {

          gsap.from(item.querySelector('.card__img img'), {
              scale: 1.5,
              filter: 'blur(8px)',
              scrollTrigger: {
                  trigger: item,
                  containerAnimation: ScrollTrigger.getAll()[0].animation,
                  start: 'left right',
                  end: 'left center',
                  scrub: true
              },
          });

      });
        
      if (itemSpeed.length > 0) {
          itemSpeed.forEach((el) => {
            gsap.to(el, {
              x:el.getAttribute("data-speed") * 100,
                ease: "none",
                scrollTrigger: {
                    start: "0",
                    end: window.innerHeight + 'px',
                    invalidateOnRefresh: true,
                    scrub: true,
                }
            });
          });
      }

    }

}
