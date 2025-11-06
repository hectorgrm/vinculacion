// /recidencia/assets/js/form-validation-es.js
// ======================================================
// 🌐 Validaciones HTML5 traducidas al español
// Compatible con todos los formularios del sistema Vinculación
// ======================================================

document.addEventListener("DOMContentLoaded", () => {
  // Detectar todos los formularios de la página
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    // Evitar que el navegador muestre mensajes por defecto en inglés
    form.addEventListener(
      "invalid",
      (event) => {
        event.preventDefault();
        const field = event.target;

        // Reset del mensaje previo
        field.setCustomValidity("");

        // Traducciones según el tipo de error
        if (field.validity.valueMissing) {
          field.setCustomValidity("Por favor, completa este campo.");
        } else if (field.validity.typeMismatch) {
          if (field.type === "email") {
            field.setCustomValidity("Por favor, introduce una dirección de correo válida.");
          } else if (field.type === "url") {
            field.setCustomValidity("Por favor, introduce una URL válida (ejemplo: https://www.ejemplo.com).");
          }
        } else if (field.validity.patternMismatch) {
          field.setCustomValidity("El formato ingresado no es válido.");
        } else if (field.validity.tooShort) {
          field.setCustomValidity(`El texto es demasiado corto. Mínimo ${field.minLength} caracteres.`);
        } else if (field.validity.tooLong) {
          field.setCustomValidity(`El texto es demasiado largo. Máximo ${field.maxLength} caracteres.`);
        } else if (field.validity.rangeUnderflow) {
          field.setCustomValidity(`El valor es demasiado bajo. Mínimo permitido: ${field.min}.`);
        } else if (field.validity.rangeOverflow) {
          field.setCustomValidity(`El valor es demasiado alto. Máximo permitido: ${field.max}.`);
        } else if (field.validity.stepMismatch) {
          field.setCustomValidity("El valor ingresado no cumple con el incremento permitido.");
        } else if (field.validity.badInput) {
          field.setCustomValidity("Por favor, introduce un valor válido.");
        } else if (field.validity.customError) {
          // Si el campo ya tenía un mensaje personalizado, no lo sobrescribimos
          return;
        } else {
          field.setCustomValidity("");
        }

        // Mostrar el mensaje
        field.reportValidity();
      },
      true
    );

    // Limpiar mensaje cuando el usuario escriba
    form.addEventListener("input", (event) => {
      event.target.setCustomValidity("");
    });
  });
});
