document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("cfp-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Validation
    const name = document.getElementById("cfp-name");
    const email = document.getElementById("cfp-email");
    const phone = document.getElementById("cfp-phone");
    const message = document.getElementById("cfp-message");

    let valid = true;
    let errorMessage = "";

    // Check if fields are empty
    if (!name.value.trim()) {
      valid = false;
      errorMessage += "Name is required.\n";
    }
    if (!email.value.trim()) {
      valid = false;
      errorMessage += "Email is required.\n";
    } else if (!validateEmail(email.value)) {
      valid = false;
      errorMessage += "Please enter a valid email address.\n";
    }
    if (!phone.value.trim()) {
      valid = false;
      errorMessage += "Phone is required.\n";
    }
    if (!message.value.trim()) {
      valid = false;
      errorMessage += "Message is required.\n";
    }

    if (!valid) {
      alert(errorMessage);
      return;
    }

    // AJAX form submission if validation passes
    let formData = new FormData(form);
    fetch(cfp_ajax.ajax_url, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        document.getElementById("cfp-response").innerHTML = data.message;
        form.reset();
      })
      .catch((error) => console.error("Error:", error));
  });

  // Validate email format
  function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
  }
});
