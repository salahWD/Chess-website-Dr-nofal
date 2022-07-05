function $(element) {
  let select = document.querySelectorAll(element);
  if (select.length > 1) {
    return [...select];
  }else if (select.length == 1) {
    return select[0];
  }else {
    return false;
  }
}

function customAlert(text) {
  let ele = document.createElement("div");
  ele.classList.add("alert", "alert-success", "animate__animated", "animate__fadeInDown");
  ele.innerHTML = text;
  $("body").append(ele);
  setTimeout(() => {
    ele.classList.remove("animate__fadeInDown");
    ele.classList.add("animate__fadeOutUp");
  }, 1500);
}

function showError(element, error) {
  let parent = $(`#${element}`).parentElement;
  parent.classList.add("is-wrong");
  $(`#${element}-error`).innerHTML = error;
  parent.scrollIntoView({
    behavior: 'smooth'
  });

}

function removeErrors() {
  let errors = document.querySelectorAll(".is-wrong");
  if (errors.length > 0) {
    console.log(errors);
    errors.forEach(err => {
      err.classList.remove("is-wrong");
    });
  }
  customAlert("done successfuly");
}

function deleteItemConfirm(type, id, token, element) {
  
  let container = document.createElement("div");
  container.classList.add("relative-container");

  let overlay = document.createElement("div");
  overlay.classList.add("overlay", "animate__animated", "animate__fadeInUp");
  overlay.addEventListener("click", function () {
    this.parentElement.remove();
  });

  let model = document.createElement("div");
  model.classList.add("model");

  let title = document.createElement("h3");
  title.classList.add("title");
  title.innerHTML = "Are you sure you want to delete this course?";

  let btnContainer = document.createElement("div");
  btnContainer.classList.add("btn-container");
  
  let yes = document.createElement("button");
  yes.classList.add("btn", "btn-danger");
  yes.innerHTML = "delete <i class=\"fa fa-trash\"></i>";

  yes.addEventListener("click", function () {
    deleteItem(type, id, token, element);
    this.parentElement.parentElement.parentElement.remove();
  });

  let no = document.createElement("button");
  no.classList.add("btn", "btn-primary");
  no.innerHTML = "cancel <i class=\"fa-solid fa-arrow-left\"></i>";
  
  no.addEventListener("click", function () {
    this.parentElement.parentElement.parentElement.remove();
  });

  model.append(title);
  btnContainer.append(no);
  btnContainer.append(yes);
  model.append(btnContainer);
  container.append(overlay);
  container.append(model);
  document.body.append(container);

}

function deleteItem(type, id, token, element) {
  const items = ["article", "course"];
  let form = new FormData();
  form.append("action", "delete");
  form.append("token", token);
  form.append(`${items[type]}_id`, id);
  fetch(`http://drnofal.test/api/${items[type]}`, {
    method: "POST",
    body: form,
  }).then(res=>res.text())
    .then(function (data) {
      let response = JSON.parse(data);

      let parent = element.parentElement.parentElement;
      if (response.success) {
        parent.classList.add("animate__fadeOut");
        setTimeout(() => {
          parent.remove();
        }, 1300);
      }else {
        parent.classList.add("error");
        setTimeout(() => {
          parent.classList.remove("error");
        }, 1500);
      }

  });

}

function savingCourse(courseId) {

  let form = new FormData($("form"));
  form.append("action", "update");
  form.append("course_id", courseId);
  fetch("http://drnofal.test/api/course", {
    method: "POST",
    body: form,
  }).then(res=>res.text())
    .then(function (data) {
      let response = JSON.parse(data);
      if (response.success) {
        removeErrors();
      }else {
        if (Object.entries(response.errors).length > 0) {
          console.log(response.errors);
          for(const [errName, errVal] of Object.entries(response.errors)) {
            console.log(`${errName}: ${errVal}`);
            showError(errName, errVal);
          }
        }
      }
  });

}

function savingData(editor, token, articleId) {

  editor.save().then((articleContent) => {
    let form = new FormData($("form"));
    form.append("action", "update");
    form.append("token", token);
    form.append("article_id", articleId);
    form.append("content", JSON.stringify(articleContent));
    fetch("http://drnofal.test/api/article", {
      method: "POST",
      body: form,
    }).then(res=>res.text())
      .then(function (data) {
        let response = JSON.parse(data);
        if (response.success) {
          removeErrors();
        }else {
          console.log(response.errors);
          if (Object.keys(response.errors).length > 0) {
            for (const [errName, errVal] of Object.entries(response.errors)) {
              showError(errName, errVal);
            }
          }
        }
    });
    
  });

}

async function getContent(token, articleId) {

  try {

    let req = new FormData();
    req.append("action", "get_content");
    req.append("token", token);
    req.append("article_id", articleId);

    let res = await fetch("http://drnofal.test/api/article", {
      method: 'POST',
      body: req,
    });
    res = await res.json();
    return res;

  } catch (error) {
      console.error(error);
  }
}

document.addEventListener('DOMContentLoaded', function() {

  if ($("#navbar-trigger")) {
    $("#navbar-trigger").addEventListener("click", function () {
      $("#dashboard").classList.toggle("active-navbar");
    });
    $("#profile-trigger").addEventListener("click", function () {
      $("#profile").classList.toggle("active");
      // $("#profile").classList.toggle("animate__fadeInUp");
    });
    $("#profile").addEventListener("click", function (e) {
      console.log("clicked");
      e.stopPropagation();
    });
  }

  if ($("#content")) {
    const TOKEN = $("#token").value;
    const articleId = window.location.pathname.split("/")[3];// <= id is 3 ['', dashboard, article, 3]
    let trigger = true;

    getContent(TOKEN, articleId).then(text =>{

      let data;
      try {
        data = JSON.parse(text.content);
      } catch (e) {
        data = {};
      }

      window.editor = new EditorJS({
        holder: "content",
        placeholder: 'Let`s Write An Awesome Article!',
        tools: {
          header: {
            class: Header,
            shortcut: 'CMD+SHIFT+H',
            config: {
              placeholder: 'Enter a header',
              levels: [2, 3, 4],
              defaultLevel: 3
            },
            inlineToolbar: ['link'],
          },
          image: {
            class: ImageTool,
            config: {
              endpoints: {
                byFile: 'http://drnofal.test/api/upload/image', // Your backend file uploader endpoint
                byUrl: 'http://drnofal.test/api/fetch/image', // Your endpoint that provides uploading by Url
              },
              types: "image/png, image/jpeg, image/webp",
            }
          },
        },  
        data: data,
      });
    });

    $("#save-info").addEventListener("click", function (e) {
      if (trigger) {
        trigger = false;
        setTimeout(() => {
          trigger = true;
        }, 3000);
        savingData(window.editor, TOKEN, articleId);
      }
    });
  
    $("#save-content").addEventListener("click", function (e) {
      if (trigger) {
        trigger = false;
        setTimeout(() => {
          trigger = true;
        }, 3000);
        savingData(window.editor, TOKEN, articleId);
      }
    });

  }

  if ($("#course")) {
    const CourseId = window.location.pathname.split("/")[3];// <= id is 3 ['', dashboard, course, 3]
    let trigger = true;
    $("#save-info").addEventListener("click", function (e) {
      if (trigger) {
        trigger = false;
        setTimeout(() => {
          trigger = true;
        }, 3000);
        savingCourse(CourseId);
      }
    });
  }

  if ($("#publish")) {
    let publicInput = $("#public-value");
    let icon = $("#public-icon");
    let i = publicInput.value == 1 ? 1: 0;
    $("#publish").addEventListener("click", function () {
      if (i == 0) {
        i = 1;
      }else {
        i = 0;
      }
      publicInput.value = i;
      this.classList.toggle("btn-danger");
      this.classList.toggle("btn-primary");
      icon.classList.toggle("fa-eye-slash");
      icon.classList.toggle("fa-eye");
    });
  }

  if ($("#manage-table")) {
    let table = $("#manage-table");
    const type = table.dataset.type;
    $(".btn-remove-item").forEach(btn => {
      const id = btn.dataset.id;
      let trigger = true;
      btn.addEventListener("click", function (e) {
        if (trigger) {

          trigger = false;
          setTimeout(() => {
            trigger = true;
          }, 2500);

          if (type == 1) {
            deleteItemConfirm(1, id, table.dataset.token, this);
          }else {
            deleteItemConfirm(0, id, table.dataset.token, this);
          }

        }
      })
    });
  }

});
