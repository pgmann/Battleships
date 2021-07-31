if (window.location.hash.length > 1) {
  bootstrap.Modal.getOrCreateInstance(joinModal).show();
  inviteCode.value = window.location.hash.substring(1);
}

// show form validation on submit
var forms = document.querySelectorAll(".needs-validation");
Array.prototype.slice.call(forms).forEach(function (form) {
  form.addEventListener("submit", function (event) {
    $(form).find(".form-control").removeClass("is-valid is-invalid");
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopImmediatePropagation();
      event.stopPropagation();
      form.querySelector(".form-control:invalid").focus();
    }

    form.classList.add("was-validated");
  }, false);
});

createModal.addEventListener('shown.bs.modal', function (event) {
  createName.focus();
});

joinModal.addEventListener('shown.bs.modal', function (event) {
  joinName.focus();
});

createForm.addEventListener("submit", function (event) {
  event.preventDefault();

  bootstrap.Modal.getInstance(createModal).hide();
  bootstrap.Modal.getOrCreateInstance(waitingModal).show();
  $.get("/data.php?name=" + createName.value + "&size=" + boardSize.value, (data) => {
    inviteCodeDisplay.innerText = data.invite;
    var inviteUrl = window.location.origin + "/#" + data.invite;
    new QRCode(inviteQR, {
      text: inviteUrl,
      width: 128,
      height: 128,
      correctLevel: QRCode.CorrectLevel.M
    });
    inviteQR.title = "";
    for (let qr of inviteQR.children) {
      // qr is displayed on either an img or canvas depending on the browser
      qr.dataset.title = "Click to copy the URL";
      qr.onclick = () => {
        navigator.clipboard.writeText(inviteUrl);
        qr.dataset.title = "Copied!";
        updateTooltip();
      }
    }
  });

  waiter = window.setInterval(() => {
    $.get("/data.php", (data) => {
      if (data.opponent) {
        window.location.hash = "";
        window.location.pathname = "/play.php";
      }
    });
  }, 1000);
});

waitingModal.addEventListener('hidden.bs.modal', function (event) {
  $.get("/data.php?end", (data) => {
    inviteCodeDisplay.innerText = "generating";
    inviteQR.innerHTML = "";
  });
  window.clearInterval(waiter);
});

joinForm.addEventListener("submit", function (event) {
  event.preventDefault();

  $.get("/data.php?name=" + joinName.value + "&invite=" + inviteCode.value, (data) => {
    joinForm.classList.remove("was-validated"); // this client-side validation would override the server side validation
    joinName.classList.add("is-valid");
    if (data.error) {
      inviteCodeError.innerText = data.error;
      inviteCodeError.style.display = "";
      inviteCode.classList.add("is-invalid");
      inviteCode.classList.remove("is-valid");
      inviteCode.focus();
    } else {
      inviteCode.classList.remove("is-invalid");
      inviteCode.classList.add("is-valid");
      window.location.hash = "";
      window.location.pathname = "/play.php";
    }
  });
});

inviteCode.addEventListener('input', function (event) {
  inviteCodeError.style.display = "none";
});