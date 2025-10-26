(function (global) {
  'use strict';

  var doc = global.document;

  function removeToastAfterFade(toast) {
    toast.addEventListener(
      'transitionend',
      function () {
        toast.remove();
      },
      { once: true }
    );
  }

  function showToast(message) {
    if (typeof message === 'string') {
      message = message.trim();
    }

    if (!message) {
      return;
    }

    var toast = doc.createElement('div');
    toast.className = 'toast toast-success';
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');
    toast.textContent = message;
    doc.body.appendChild(toast);

    global.requestAnimationFrame(function () {
      toast.classList.add('is-visible');
    });

    global.setTimeout(function () {
      toast.classList.remove('is-visible');
      removeToastAfterFade(toast);
    }, 4000);
  }

  function initFromDataset() {
    var dataElement = doc.getElementById('empresa-success-toast');
    if (!dataElement) {
      return;
    }

    var message = dataElement.getAttribute('data-toast-message');
    dataElement.remove();

    showToast(message);
  }

  var freeze =
    typeof Object.freeze === 'function'
      ? Object.freeze
      : function (value) {
          return value;
        };

  global.EmpresaSuccessToast = freeze({
    show: showToast,
    initFromDataset: initFromDataset,
  });

  initFromDataset();
})(window);
