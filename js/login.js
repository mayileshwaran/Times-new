// ✅ Toggle password visibility
function togglePassword(fieldId, icon) {
  const input = document.getElementById(fieldId);
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}

// ✅ Switch between login and signup panels
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
  container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
  container.classList.remove("right-panel-active");
});

// ✅ Form validation for login
function validateLoginForm() {
  const email = document.getElementById("login_email").value;
  const password = document.getElementById("login_password").value;
  let isValid = true;

  if (!email.trim()) {
    document.getElementById("emailError").textContent = "Email is required.";
    isValid = false;
  } else {
    document.getElementById("emailError").textContent = "";
  }

  if (!password.trim()) {
    document.getElementById("passwordError").textContent = "Password is required.";
    isValid = false;
  } else {
    document.getElementById("passwordError").textContent = "";
  }

  return isValid;
}
if (window.history && window.history.pushState) {
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
        window.location.href = "login.php"; // or reload the login page
    };
}
