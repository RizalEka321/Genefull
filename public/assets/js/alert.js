// Popup
const popup = document.getElementById("popup");
const popupIcon = document.getElementById("popup-icon");
const popupTitle = document.getElementById("popup-title");
const popupMessage = document.getElementById("popup-message");
const popupLoading = document.getElementById("popup-loading");

let popupTimeout = null;

function showPopup(icon, title, message) {
  if (!popup || !popupIcon || !popupTitle || !popupMessage || !popupLoading) {
    console.error("Elemen popup tidak ditemukan!");
    return;
  }

  const validIcons = ["success", "info", "error", "no_internet"];
  if (!validIcons.includes(icon)) {
    return;
  }

  const iconSrc = "assets/img/alert/" + icon + ".png";
  console.log(iconSrc);
  popupIcon.src = iconSrc;

  popupTitle.textContent = title;
  popupMessage.textContent = message;

  popupLoading.classList.remove("success", "info", "error");
  void popupLoading.offsetWidth;
  popupLoading.classList.add(icon === "no_internet" ? "error" : icon);

  popup.classList.remove("popup-hidden");
  popup.classList.add("popup-show");

  clearTimeout(popupTimeout);
  popupTimeout = setTimeout(() => {
    popup.classList.remove("popup-show");
    popup.classList.add("popup-hidden");
  }, 1500);
}

const confirmPopup = document.getElementById("confirm-popup");
const confirmYes = document.getElementById("confirm-yes");
const confirmNo = document.getElementById("confirm-no");
const confirmClose = document.getElementById("confirm-close");
const confirmTitle = document.getElementById("confirm-title");
const confirmMessage = document.getElementById("confirm-message");

function showConfirm(title, message, callbackYes) {
  confirmTitle.textContent = title;
  confirmMessage.textContent = message;

  confirmPopup.classList.remove("popup-hidden");
  setTimeout(() => confirmPopup.classList.add("popup-show"), 10);

  confirmYes.onclick = function () {
    hideConfirm();
    if (typeof callbackYes === "function") callbackYes();
  };

  confirmNo.onclick = hideConfirm;
  confirmClose.onclick = hideConfirm;

  function hideConfirm() {
    confirmPopup.classList.remove("popup-show");
    setTimeout(() => confirmPopup.classList.add("popup-hidden"), 300);
  }
}
