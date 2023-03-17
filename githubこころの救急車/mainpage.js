const setOptionSelected = (_select) => {
  const elmOptions = Array.from(_select.children);
  elmOptions.forEach((_option) => {
    if (_option.hasAttribute("selected")) _option.removeAttribute("selected");
  });
  elmOptions[_select.selectedIndex].setAttribute("selected", "");
};

const elmSelects = [...document.querySelectorAll(".setOptionSelected")];
elmSelects.forEach((_elmSelect) => {
  _elmSelect.addEventListener("change", () => {
    setOptionSelected(_elmSelect);
  });
});

window.addEventListener(
  "load",
  function () {
    if (!localStorage.getItem("disp_popup")) {
      localStorage.setItem("disp_popup", "on");
      const body = document.querySelector("body");
      const bgPopup = document.querySelector(".bg_onetime_popup");
      const popup = document.querySelector(".onetime_popup");
      const popupTitleClose = document.querySelector(
        ".onetime_popup_title_close"
      );
      body.classList.add("open_popup");

      bgPopup.addEventListener("click", function () {
        closePopup();
      });
      popup.addEventListener("click", function (e) {
        e.stopPropagation();
      });
      popupTitleClose.addEventListener("click", function () {
        closePopup();
      });

      function closePopup() {
        body.classList.remove("open_popup");
      }
    }
  },
  false
);
