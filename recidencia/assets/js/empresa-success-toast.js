(function () {
  const dataElement = document.getElementById('empresa-success-toast');
  if (!dataElement) {
    return;
  }

  const message = dataElement.getAttribute('data-toast-message');
  dataElement.remove();

  if (!message) {
    return;
  }

  const toast = document.createElement('div');
  toast.className = 'toast toast-success';
  toast.setAttribute('role', 'status');
  toast.setAttribute('aria-live', 'polite');
  toast.textContent = message;
  document.body.appendChild(toast);

  requestAnimationFrame(function () {
    toast.classList.add('is-visible');
  });

  window.setTimeout(function () {
    toast.classList.remove('is-visible');
    toast.addEventListener(
      'transitionend',
      function () {
        toast.remove();
      },
      { once: true }
    );
  }, 4000);
})();
