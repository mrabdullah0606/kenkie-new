 /**=====================
     Custom Slick Animated js
==========================**/
 $('.slider-animate').slick({
     autoplay: true,
     autoplaySpeed: 5000,
     speed: 800,
     lazyLoad: 'progressive',
     fade: true,
     dots: true,
     arrows: true,
     prevArrow: '<button type="button" class="slick-prev hero-arrow hero-prev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>',
     nextArrow: '<button type="button" class="slick-next hero-arrow hero-next" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>',
 }).slickAnimation();