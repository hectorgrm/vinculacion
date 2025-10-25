(function (global) {
  'use strict';

  function submitIfExists(formId) {
    var form = document.getElementById(formId);
    if (form instanceof HTMLFormElement) {
      form.submit();
    }
  }

  function confirmDisable(id, name) {
    var message =
      '¿Seguro que deseas desactivar la empresa "' +
      name +
      '"?\nEsto deshabilitará su portal, convenios y plazas asociadas.';

    if (global.confirm(message)) {
      submitIfExists('disableForm-' + id);
    }
  }

  function confirmReactivate(id, name) {
    var message =
      '¿Deseas reactivar la empresa "' +
      name +
      '" y restaurar su acceso al portal?';

    if (global.confirm(message)) {
      submitIfExists('reactivateForm-' + id);
    }
  }

  var freeze = typeof Object.freeze === 'function'
    ? Object.freeze
    : function (value) {
        return value;
      };

  global.EmpresaActions = freeze({
    confirmDisable: confirmDisable,
    confirmReactivate: confirmReactivate,
  });

  global.confirmDisable = confirmDisable;
  global.confirmReactivate = confirmReactivate;
})(window);

