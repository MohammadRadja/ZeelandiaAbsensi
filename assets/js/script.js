document.addEventListener("DOMContentLoaded", function () {
  // Client-side validation
  document
    .getElementById("loginForm")
    .addEventListener("submit", function (event) {
      var form = this;
      var isValid = true;

      // Reset previous validation feedback
      form.classList.remove("was-validated");

      // Check ID Karyawan
      if (!form.IDKaryawan.value.trim()) {
        form.IDKaryawan.classList.add("is-invalid");
        isValid = false;
      } else {
        form.IDKaryawan.classList.remove("is-invalid");
        form.IDKaryawan.classList.add("is-valid");
      }

      // Check Password
      if (!form.Password.value.trim()) {
        form.Password.classList.add("is-invalid");
        isValid = false;
      } else {
        form.Password.classList.remove("is-invalid");
        form.Password.classList.add("is-valid");
      }

      if (!isValid) {
        event.preventDefault();
        event.stopPropagation();
        form.classList.add("was-validated");
      }
    });
});
