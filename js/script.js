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

function createCommentElement(src = "http://drnofal.test/uploads/img/unknown.jpg", name, userText) {

  let comment = document.createElement("div");
  comment.classList.add("comment");

  let body = document.createElement("div");
  body.classList.add("body");

  comment.appendChild(body);
  
  let profile = document.createElement("div");
  profile.classList.add("profile");
  body.appendChild(profile);
  
  let userImg = document.createElement("div");
  userImg.classList.add("user-img");

  let userName = document.createElement("div");
  userName.classList.add("user-name");
  userName.innerText = name;

  profile.appendChild(userImg);
  profile.appendChild(userName);
  
  let img = document.createElement("img");
  img.setAttribute("src", src);
  userImg.appendChild(img);

  let text = document.createElement("div");
  text.classList.add("text");
  body.appendChild(text);
  text.innerText = userText;

  let actions = document.createElement("div");
  actions.classList.add("actions");
  comment.appendChild(actions);

  let reply = document.createElement("button");
  reply.classList.add("btn", "btn-transparent", "reply");
  let icon = document.createElement("i");
  icon.classList.add("fa-solid", "fa-reply");
  reply.appendChild(icon);
  reply.append(document.createTextNode(" save"));
  actions.appendChild(reply);

  let date = document.createElement("p");
  date.classList.add("date");
  const now = new Date();
  const yyyy = now.getFullYear();
  let mm = now.getMonth() + 1; // Months start at 0!
  let dd = now.getDate();

  if (dd < 10) dd = '0' + dd;
  if (mm < 10) mm = '0' + mm;

  date.innerText = `${yyyy}-${mm}-${dd}`;
  actions.appendChild(date);

  return comment;

}

function insertreply(formEl, reply_on, parent) {
  form = new FormData(formEl);
  form.append("replied_id", reply_on);
  let article_id = document.getElementById("comment-form").querySelector("input[name=\"article_id\"]").value;
  form.append("article_id", article_id);

  fetch("http://drnofal.test/add/comment", {
    method: "POST",
    body: form,
  }).then(res=>res.text())
    .then(function (data) {
      let response = JSON.parse(data);
      window.toggler = true;
      if (response.success) {
        let src = parent.querySelector(".profile img").getAttribute("src");
        let name = parent.querySelector(".profile .user-name").innerText;
        let userText = formEl.querySelector("textarea").value;
        let reply = createReplyElement(src, name, userText);
        showreply(reply, parent);
        formEl.classList.add("remove");
        setTimeout(() => {
          formEl.remove();
        }, 1000);
      }else {
        console.error(response.errors);
      }
  });
}

function createReplyElement(src = "http://drnofal.test/uploads/img/unknown.jpg", name, userText, date) {

  let reply = document.createElement("div");
  reply.classList.add("reply");

  let body = document.createElement("div");
  body.classList.add("body");
  reply.appendChild(body);
  
  let profile = document.createElement("div");
  profile.classList.add("profile");
  body.appendChild(profile);
  
  let tag = document.createElement("span");
  tag.classList.add("tag");
  tag.innerHTML = `<i class="fa-solid fa-reply"></i> ${name}`;
  profile.appendChild(tag);
  
  let userImg = document.createElement("div");
  userImg.classList.add("user-img");
  profile.appendChild(userImg);
  
  let img = document.createElement("img");
  img.setAttribute("src", src);
  userImg.appendChild(img);
  
  let userName = document.createElement("sapn");
  userName.classList.add("user-name");
  userName.innerText = name;
  profile.appendChild(userName);

  let text = document.createElement("div");
  text.classList.add("text");
  text.innerText = userText;
  body.appendChild(text);

  let actions = document.createElement("div");
  actions.classList.add("actions");
  reply.appendChild(actions);

  let dateEl = document.createElement("p");
  dateEl.classList.add("date");
  dateEl.innerText = date;
  actions.appendChild(dateEl);

  return reply;

}

function showreply(reply, parent) {

  let replies;
  if (!parent.querySelector(".replies")) {
    replies = document.createElement("div");
    replies.classList.add("replies");
    parent.appendChild(replies);
  }else {
    replies = parent.querySelector(".replies");
  }

  replies.appendChild(reply);

}

function showReplies(parent, replyData) {

  form = new FormData();
  form.append("comment_id", parent.querySelector(".comment input.id").value);
  form.append("offset", replyData.offsetCounter);
  fetch("http://drnofal.test/comment/replies", {
    method: "POST",
    body: form,
  }).then(res=>res.text())
    .then(function (data) {
      let response = JSON.parse(data);
      if (response.success) {
        replyData.trigger = true;
        replyData.offsetCounter++;
        response.data.forEach(reply => {
          let replyEl = createReplyElement(`http://drnofal.test/uploads/img/${reply.user_image}`, reply.user_name, reply.comment, reply.date);
          showreply(replyEl, parent);
        });
        let number = parent.querySelector(".show-replies-btn .number");
        number.innerText = (+number.innerText) > 4 ? +number.innerText - 4: 0;
      }else {
        let number = parent.querySelector(".show-replies-btn .number");
        number.innerText = 0;
      }
  });

}

function showCommentForm(btn) {

  if (!btn.parentElement.querySelector("form textarea")) {
    let formContrainer = document.createElement("div");
    formContrainer.classList.add("reply-container");

    let form = document.createElement("form");
    form.setAttribute("action", "http://drnofal.test/add/comment");
    form.setAttribute("method", "POSt");

    formContrainer.appendChild(form);
    
    let comment = document.createElement("textarea");
    comment.classList.add("comment");
    comment.setAttribute("placeholder", "write your reply");
    comment.setAttribute("name", "comment");

    form.appendChild(comment);
    
    let btnsContainer = document.createElement("div");
    btnsContainer.classList.add("btns-container");
    form.appendChild(btnsContainer);

    let cancelBtn = document.createElement("button");
    cancelBtn.classList.add("btn", "btn-transparent", "cancel-btn");
    cancelBtn.setAttribute("type", "button");
    cancelBtn.innerText = "Cancel";
    btnsContainer.appendChild(cancelBtn);
    cancelBtn.addEventListener("click", function () {
      this.parentElement.parentElement.classList.add("remove");
      setTimeout(() => {
        this.parentElement.parentElement.remove();
      }, 1000);
    });

    let sendBtn = document.createElement("button");
    sendBtn.classList.add("btn", "btn-warning");
    sendBtn.setAttribute("type", "button");
    sendBtn.innerHTML = "<i class=\"fa-solid fa-paper-plane\"></i> comment";
    btnsContainer.appendChild(sendBtn);

    btn.parentElement.appendChild(form);
    window.toggler = true;
    sendBtn.addEventListener("click", function () {
      if (window.toggler) {
        window.toggler = false;
        insertreply(this.parentElement.parentElement, this.parentElement.parentElement.parentElement.parentElement.querySelector("input.id").value, this.parentElement.parentElement.parentElement.parentElement);
        setTimeout(() => {
          window.toggler = true;
        }, 3500);
      }
    });
  }
}

function insertComment(btn) {

  let form = new FormData($("#comment-form")[0]);
  
  fetch("http://drnofal.test/add/comment", {
    method: "POST",
    body: form,
  }).then(res=>res.text())
    .then(function (data) {
      let response = JSON.parse(data);
      let parent = $(`.input-holder`)[0];
      let error = parent.querySelector(".error");
      let commentEl = parent.querySelector("#comment");
      let src = parent.querySelector("img").getAttribute("src");
      let username = parent.querySelector("label .name").innerText;
      let commentText = commentEl.value;
      if (response.success) {
        commentEl.value = '';
        if (error.classList.contains("animate__fadeIn")) {
          error.classList.remove("animate__fadeIn");
          error.classList.add("animate__fadeOut");
        }
        $(`.input-holder`)[0].classList.remove("is-wrong");
        btn.innerHTML = `${btn.dataset.value} <i class="fa-solid fa-paper-plane"></i>`;
        
        let comment = createCommentElement(src, username, commentText);
        $("#comments")[0].prepend(comment);

      }else {

        console.error(response.errors);

        parent.classList.add("is-wrong");
        error.innerHTML = response.errors;
        error.classList.remove("animate__fadeOut");
        error.classList.add("animate__fadeIn");

        const y = parent.getBoundingClientRect().top + window.pageYOffset - 180;

        window.scrollTo({top: y, behavior: 'smooth'});
        btn.innerHTML = `${btn.dataset.value} <i class="fa-solid fa-paper-plane"></i>`;
      }
  });

}

function activeForm(activeFormId) {

  let loginForm = $("#form-login")[0];
  let signupForm = $("#form-signup")[0];
  let formContrainer = $("#form-container")[0];

  if (activeFormId==1) {

    formContrainer.classList.add("active");
    signupForm.classList.add("animate__fadeInRight");
    loginForm.classList.remove("animate__fadeInLeft");
    loginForm.classList.add("animate__fadeOutLeft");

  }else {

    loginForm.classList.add("animate__fadeInLeft");
    formContrainer.classList.remove("active");
    signupForm.classList.remove("animate__fadeInRight");
    signupForm.classList.add("animate__fadeOutRight");

  }
  
}

function firestActiveForm(activeFormId) {

  let loginForm = $("#form-login")[0];
  let signupForm = $("#form-signup")[0];
  let formContrainer = $("#form-container")[0];

  if (activeFormId==1) {

    formContrainer.classList.add("active");
    signupForm.classList.add("animate__fadeInRight");

  }else {

    formContrainer.classList.remove("active");
    loginForm.classList.add("animate__fadeInLeft");

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

  if ($("#profile")[0]) {

    $("#profile-trigger")[0].addEventListener("click", function () {
      $("#profile")[0].classList.toggle("active");
    });
  }

  if ($("#form-login")[0]) {

    if (window.location.hash == "#signup") {
      firestActiveForm(1);// form signup is active
    }else {
      firestActiveForm(2);// form login is active
    }

    let loginToggle = $("#login-toggle")[0];
    let signupToggle = $("#signup-toggle")[0];

    loginToggle.addEventListener("click", function () {
      activeForm(1);
    });
    signupToggle.addEventListener("click", function () {
      activeForm(2);
    });

  }

  if ($("#change-image")[0]) {
    let btn = $("#change-image")[0];
    let inputCreated = false;
    let image = $("#image")[0];
    let input = null;
    btn.addEventListener("click", function () {
      if (!inputCreated) {
        input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("name", "image");
        input.setAttribute("accept", "image/png, image/jpeg, image/webp, image/jpg");
        input.classList.add("d-none", "input-h");
        btn.parentElement.append(input);
        input.addEventListener("change", function () {
          image.src = URL.createObjectURL(input.files[0]);
        });
        inputCreated = true;
      }
      input.click();
    });
  }// image input

  if ($("#comment-form")[0]) {
    let trig = true;
    $("#comment-send")[0].addEventListener("click", function (e) {
      e.preventDefault();
      if (trig) {
        trig = false;
        this.style.minWidth = `${this.getBoundingClientRect().width}px`;
        this.innerHTML = `<i class="load fa fa-spinner"></i>`;
        insertComment(this);
        setTimeout(() => {
          trig = true;
        }, 3500);
      }
    });
    $("#comment")[0].addEventListener("focus", function (e) {
      $(`.input-holder`)[0].classList.remove("is-wrong");
    });

    if ($(".comments .comment").length > 0) {
      let repliesBtns = $(".comments .show-replies-btn");
      repliesBtns.forEach(btn => {
        let replyData = {offsetCounter: 1, trigger: true};
        btn.addEventListener("click", function () {
          if (replyData.trigger) {
            showReplies(this.parentElement.parentElement, replyData);
            replyData.trigger = false;
          }
        });
      });
    }

  }

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