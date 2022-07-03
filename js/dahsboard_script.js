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
  const errors = [
    "The url must be unique, this url-title has been used before.",
  ];
  $(`#${element}`).parentElement.classList.add("is-wrong");
  let msg = $(`#${element}-error`);
  msg.innerHTML = errors[error - 1];
  $(`#${element}-label`).scrollIntoView({
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
          if (response.error == "1") {
            // error 1 => duplicate entry
            showError("url-title", response.error);
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
      $("#profile").classList.toggle("animate__fadeOutUp");
      $("#profile").classList.toggle("animate__fadeInUp");
    });
  }

  if ($("#content")) {
    console.log($("#content"));
    const TOKEN = $("#token").value;
    const articleId = $("#article_id").value;
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

});

