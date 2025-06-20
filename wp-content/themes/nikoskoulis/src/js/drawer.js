import { gsap } from 'gsap';
import ImageHoverLink from './ImageHoverLink';

export default class Drawer {
  constructor({ drawerSelector, buttonSelector }) {
    this.DOM = { el: drawerSelector };
    this.body = document.body;
    this.drawer = document.querySelector(drawerSelector);
    this.button = document.querySelector(buttonSelector);
    this.line1 = this.button.querySelector('span:first-child');
    this.line2 = this.button.querySelector('span:last-child');
    this.bg = this.drawer.querySelector('.drawer__bg');
    this.nav = this.drawer.querySelector('.drawer__nav');
    this.navItems = this.nav.querySelectorAll('.drawer__item');
    this.navLinks = this.nav.querySelectorAll('.drawer__link');
    this.navLinksInner = this.nav.querySelectorAll('.drawer__link-inner');
    this.imageLinks = Array.from(this.nav.querySelectorAll('a')).filter(link => link.dataset.hover && link.dataset.hover.trim() !== '');
    this.footer = this.drawer.querySelector('.drawer__footer');
    this.dropdowns = this.nav.querySelectorAll('.drawer__item > .drawer__dropdown');
    this.isOpen = false;


    this.animatableProperties = {
      // translationX
      tx: {previous: 0, current: 0, amt: 0.08},
      // translationY
      ty: {previous: 0, current: 0, amt: 0.08},
      // Rotation angle
      rotation: {previous: 0, current: 0, amt: 0.06}
    };

    this.menuItems = [];
    [...this.imageLinks].forEach((item, pos) => this.menuItems.push(new ImageHoverLink(item, pos, this.animatableProperties)));


    if (!this.drawer || !this.button || !this.line1 || !this.line2) return;

    // Initial
    gsap.set(this.bg, { scaleY: 0, transformOrigin: 'top' });
    gsap.set(this.nav, { autoAlpha: 0 });
    gsap.set(this.navLinksInner, { yPercent: 100, autoAlpha: 0 });
    gsap.set(this.footer, { y: 20, autoAlpha: 0 });


    // Drawer open/close
    this.drawer_tl = gsap.timeline({ paused: true, reversed: true });
    this.drawer_tl
      .to(this.drawer, { autoAlpha: 1, duration: 0.01 })
      .to(this.bg, {
        scaleY: 1,
        duration: 0.6,
        ease: 'ease.out',
      }, '<')
      .to(this.nav, {
        autoAlpha: 1,
        duration: 0.5,
      }, '<')
      .to(this.navLinksInner, {
        yPercent: 0,
        autoAlpha: 1,
        duration: 0.5,
        stagger:  (i, _, list) => Math.min(0.25, 0.5 / list.length) * i,
        ease: 'power2.out',
      }, '<')
      .to(this.footer, {
        y: 0,
        autoAlpha: 1,
        duration: 0.3,
        ease: 'power2.out',
      }, '-=0.1');

    // Burger icon animation
    this.burger_tl = gsap.timeline({ paused: true, reversed: true });
    this.burger_tl
      .to(this.line1, {
        y: 3,
        rotate: 45,
        transformOrigin: 'center center',
        duration: 0.5,
        ease: 'power2.out',
      }, 0)
      .to(this.line2, {
        y: -3,
        rotate: -45,
        transformOrigin: 'center center',
        duration: 0.5,
        ease: 'power2.out',
      }, 0);

    // Button event listener
    this.button.addEventListener('click', () => this.toggle());


    // Drawer dropdowns
    this.dropdowns.forEach(dropdown => {
      const children = dropdown.querySelectorAll('.drawer__dropdown-link-inner');
      gsap.set(children, { yPercent: 100, autoAlpha: 0 });
      dropdown._children = children;
      dropdown._isOpen = false;
      dropdown._isAnimating = false;
      dropdown.style.visibility = 'hidden'; 

      const dropdownToggler = dropdown.previousElementSibling;
      if (dropdownToggler && dropdownToggler.classList.contains('drawer__link')) {
        dropdownToggler.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();

          this.toggleDropdown(dropdown);
        });
      }
    });


  }

  async toggleDropdown(activeDropdown) {
    if (activeDropdown._isAnimating) return;

    const closePromises = [];
    this.dropdowns.forEach(dropdown => {
      if (dropdown !== activeDropdown && dropdown._isOpen) {
        dropdown.classList.remove('is-open');
        dropdown.previousElementSibling.classList.remove('active');
        closePromises.push(this._closeDropdown(dropdown));
      }
    });

    await Promise.all(closePromises);

    if (activeDropdown._isOpen) {
      await this._closeDropdown(activeDropdown);
    } else {
      await this._openDropdown(activeDropdown);
    }
  }
  _openDropdown(dropdown) {
    dropdown._isAnimating = true;
    return new Promise(resolve => {
      const isMobile = window.matchMedia('(max-width: 1023px)').matches;
      
      if (!isMobile) {
        gsap.to(dropdown._children, {
          yPercent: 0,
          autoAlpha: 1,
          duration: 0.5,
          stagger:  (i, _, list) => Math.min(0.5, 1 / list.length) * i,
          ease: 'ease.inOut',
          onStart: () => {
            dropdown.style.visibility = 'visible';
            dropdown.classList.add('is-open');
            dropdown.previousElementSibling.classList.add('active');
          },
          onComplete: () => {
            dropdown._isAnimating = false;
            dropdown._isOpen = true;
            resolve();
          }
        }); 
      } else {
        gsap.to(dropdown._children, {
          yPercent: 0,
          autoAlpha: 1,
          duration: 0.25,
          ease: 'ease.inOut',
          onStart: () => {
            dropdown.style.visibility = 'visible';
            dropdown.classList.add('is-open');
            dropdown.previousElementSibling.classList.add('active');
          },
          onComplete: () => {
            dropdown._isAnimating = false;
            dropdown._isOpen = true;
            resolve();
          }
        });
      }
    });
  }

  _closeDropdown(dropdown) {
    dropdown._isAnimating = true;
    const isMobile = window.matchMedia('(max-width: 1023px)').matches;
    return new Promise(resolve => {
      if (!isMobile) {
        gsap.to(dropdown._children, {
          yPercent: 100,
          autoAlpha: 0,
          duration: 0.5,
          ease: 'expo.out',
          onComplete: () => {
            dropdown.style.visibility = 'hidden';
            dropdown._isAnimating = false;
            dropdown._isOpen = false;
            dropdown.classList.remove('is-open');
            dropdown.previousElementSibling.classList.remove('active');
            resolve();
          }
        });
      } else {
        gsap.to(dropdown._children, {
          yPercent: 100,
          autoAlpha: 0,
          duration: 0.2,
          ease: 'expo.out',
          onComplete: () => {
            dropdown.style.visibility = 'hidden';
            dropdown._isAnimating = false;
            dropdown._isOpen = false;
            dropdown.classList.remove('is-open');
            dropdown.previousElementSibling.classList.remove('active');
            resolve();
          }
        });
      }
    });
  }

  open() {
    this.body.classList.add('is-drawer-open');
    this.button.setAttribute('aria-expanded', 'true');
    this.drawer_tl.play();
    this.burger_tl.play();
    this.isOpen = true;
  }

  close() {
    this.body.classList.remove('is-drawer-open');
    this.button.setAttribute('aria-expanded', 'false');
    this.drawer_tl.reverse();
    this.burger_tl.timeScale(2).reverse();
    this.isOpen = false;

  this.dropdowns.forEach(dropdown => {
    if (dropdown._isOpen) {
      this._closeDropdown(dropdown);
    }

    const toggler = dropdown.previousElementSibling;
    if (toggler && toggler.classList.contains('drawer__link')) {
      toggler.classList.remove('is-dropdown-open');
    }
  });

  }

  toggle() {
    this.isOpen ? this.close() : this.open();
  }
}
