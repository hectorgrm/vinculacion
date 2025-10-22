function submitIfExists(formId) {
  var form = document.getElementById(formId);
  if (form) {
    form.submit();
  }
}

function confirmDisable(id, name) {
  var message =
    '¿Seguro que deseas desactivar la empresa "' +
    name +
    '"?\nEsto deshabilitará su portal, convenios y plazas asociadas.';

  if (confirm(message)) {
    submitIfExists('disableForm-' + id);
  }
}

function confirmReactivate(id, name) {
  var message =
    '¿Deseas reactivar la empresa "' +
    name +
    '" y restaurar su acceso al portal?';

  if (confirm(message)) {
    submitIfExists('reactivateForm-' + id);
  }
}

