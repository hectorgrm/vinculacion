// /recidencia/assets/validaciones/js/empresa-add-validation.js
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form.form");
  if (!form) return;

  // 💡 Validaciones personalizadas específicas del formulario
  form.addEventListener("submit", (event) => {
    const nombre = document.getElementById("nombre");
    const contactoEmail = document.getElementById("contacto_email");
    const telefono = document.getElementById("telefono");

    // Validar nombre
    if (nombre.value.trim().length < 3) {
      event.preventDefault();
      nombre.setCustomValidity("El nombre de la empresa debe tener al menos 3 caracteres.");
      nombre.reportValidity();
      return;
    } else {
      nombre.setCustomValidity("");
    }

    // Validar correo
    if (contactoEmail.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contactoEmail.value)) {
      event.preventDefault();
      contactoEmail.setCustomValidity("Por favor ingresa un correo válido (ejemplo: contacto@empresa.com).");
      contactoEmail.reportValidity();
      return;
    } else {
      contactoEmail.setCustomValidity("");
    }

    // Validar teléfono
    if (telefono.value && !/^[0-9()\-\s+]{7,20}$/.test(telefono.value)) {
      event.preventDefault();
      telefono.setCustomValidity("Por favor ingresa un teléfono válido (7–20 dígitos o símbolos).");
      telefono.reportValidity();
      return;
    } else {
      telefono.setCustomValidity("");
    }
  });
});
