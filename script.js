const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signInForm = document.getElementById('signIn');
const signUpForm = document.getElementById('signup');

signUpButton.addEventListener('click', function () {
  signInForm.style.display = "none";
  signUpForm.style.display = "block";
});

signInButton.addEventListener('click', function () {
  signInForm.style.display = "block";
  signUpForm.style.display = "none";
});

document.getElementById('signupForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());

  const response = await fetch('backend.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(data).toString()
  });

  const result = await response.json();
  alert(result.message || result.error);
});

document.getElementById('signinForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());

  const response = await fetch('backend.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(data).toString()
  });

  const result = await response.json();
  alert(result.message || result.error);
});