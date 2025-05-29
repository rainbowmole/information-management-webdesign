let lastScrollY = window.scrollY;
const header = document.querySelector("header");

window.addEventListener("scroll", () => {
  if (window.scrollY > lastScrollY) {
    // Scrolling down
    header.classList.add("hidden");
  } else {
    // Scrolling up
    header.classList.remove("hidden");
  }
  lastScrollY = window.scrollY;
});

// Add margin to hero section based on header height
window.addEventListener("load", adjustHeroMargin);
window.addEventListener("resize", adjustHeroMargin);

function adjustHeroMargin() {
  const hero = document.querySelector(".hero-image");
  if (hero && header) {
    const headerHeight = header.offsetHeight;
    hero.style.marginTop = `${headerHeight}px`;
  }
}

// yung burger maging x
const menuToggle = document.getElementById('menu-toggle');
const mobileNav = document.getElementById('mobile-nav');
const icon = menuToggle.querySelector('i');

menuToggle.addEventListener('click', () => {
  mobileNav.classList.toggle('show');
  icon.classList.toggle('bx-menu');
  icon.classList.toggle('bx-x');
});
