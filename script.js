const passwordInput = document.getElementById('password');
const eyeBtn = document.querySelector('.eye-icon');

eyeBtn.addEventListener('click', () => {
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    eyeBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
    eyeBtn.textContent = isHidden ? '🙈' : '👁';
});
