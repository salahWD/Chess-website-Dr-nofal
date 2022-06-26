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
  slidesPerView: 2,
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