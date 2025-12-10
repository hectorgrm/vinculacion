<div class="empty-state">
  <div class="empty-state__icon">📝</div>
  <div class="empty-state__content">
    <h4>Sin machote registrado</h4>
    <p>Genera el machote desde la plantilla global para iniciar la revisi&oacute;n.</p>
    <?php if (!empty($machoteGenerateUrl ?? '')): ?>
      <a class="btn btn-outline" href="<?php echo htmlspecialchars($machoteGenerateUrl, ENT_QUOTES, 'UTF-8'); ?>">📝 Generar machote</a>
    <?php endif; ?>
  </div>
</div>
