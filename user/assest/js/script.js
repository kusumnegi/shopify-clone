function switchToSignup() {
  document.getElementById('signupFields').style.display = 'block';
  document.getElementById('formAction').value = 'signup';
  document.getElementById('formBtn').innerText = 'Sign Up';
  document.querySelector('.form-box h4').innerText = 'Sign Up';
  document.querySelector('.toggle-link').innerHTML =
    `Already have an account? <a href="#" onclick="switchToLogin()">Login</a>`;

  // ✅ Update tab title
  document.title = 'Sign Up - Shopify';
}

function switchToLogin() {
  document.getElementById('signupFields').style.display = 'none';
  document.getElementById('formAction').value = 'login';
  document.getElementById('formBtn').innerText = 'Login';
  document.querySelector('.form-box h4').innerText = 'Login';
  document.querySelector('.toggle-link').innerHTML =
    `Don’t have an account? <a href="#" onclick="switchToSignup()">Sign up</a>`;

  // ✅ Update tab title
  document.title = 'Login - Shopify';
}