<div class="empty-state">
  <div class="empty-state__icon">🎓</div>
  <div class="empty-state__content">
    <h4>Sin estudiantes vinculados</h4>
    <p>Registra estudiantes para dar seguimiento a sus residencias y generar su documentaci&oacute;n.</p>
    <?php if (empty($bloquearAlta ?? false)): ?>
      <a href="<?php echo htmlspecialchars($nuevoEstudianteUrl ?? '#', ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">➕ Agregar estudiante</a>
    <?php endif; ?>
  </div>
</div>
