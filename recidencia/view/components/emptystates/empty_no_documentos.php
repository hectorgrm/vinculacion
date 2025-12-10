<div class="empty-state">
  <div class="empty-state__icon">📑</div>
  <div class="empty-state__content">
    <h4>A&uacute;n no hay documentos configurados</h4>
    <p>Define los documentos requeridos para controlar el avance y las aprobaciones de esta empresa.</p>
    <?php if (!empty($documentosGestionUrl ?? '') && empty($empresaIsReadOnly ?? false)): ?>
      <a href="<?php echo htmlspecialchars($documentosGestionUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">📑 Gestionar documentos</a>
    <?php endif; ?>
  </div>
</div>
