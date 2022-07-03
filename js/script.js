const mediaQuery = window.matchMedia('(min-width: 992px)');
let postPerPage = 1;
if (mediaQuery.matches) {
  postPerPage = 2;
}

function $(element) {
  let select = document.querySelectorAll(element);
  if (Array.isArray(select)) {
    if (select.length > 1) {
      return [...select];
    }else {
      return select[0];
    }
  }else {
    return select;
  }
}

let header = $("#header")[0];
let overlay = $("#header-overlay")[0];

if ($("#header-btn")[0]) {
  $("#header-btn")[0].addEventListener("click", function () {
    overlay.classList.toggle("active");
    header.classList.toggle("active");
  });
  overlay.addEventListener("click", function () {
    overlay.classList.remove("active");
    header.classList.remove("active");
  });
}

window.addEventListener("load", function () {

  let progress = $(".progress .bar .value");
  progress.forEach(value => {
    value.style.width = `${value.dataset.percent}%`;
  });

});

const swiper = new Swiper('.swiper', {
  autoplay: {
    delay: 5000,
  },
  speed: 500,
  spaceBetween: 0,
  loop: true,
  grabCursor: true,
  pauseOnMouseEnter: true,
});

const testimonials = new Swiper('.testimonials-swiper', {
  autoplay: {
    delay: 5000,
  },
  speed: 500,
  spaceBetween: 20,
  slidesPerView: postPerPage,
  loop: true,
  grabCursor: true,
  pauseOnMouseEnter: true,
});

function animate(element) {
  
  let rect = element.getBoundingClientRect();
  if ((rect.top < window.innerHeight && rect.top > window.scrollX) || (rect.bottom > window.scrollX && rect.bottom < window.innerHeight)) {
    element.classList.add(element.dataset.animation, "animated");
  }
}

$(".animate__animated").forEach(anim => {
  animate(anim);
});

$(".animate__animated:not(.animated)").forEach(anim => {
  window.addEventListener("scroll", function () {
    animate(anim);
  });
});