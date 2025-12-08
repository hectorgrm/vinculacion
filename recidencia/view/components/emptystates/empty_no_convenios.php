<div class="empty-state">
  <div class="empty-state__icon">📄</div>
  <div class="empty-state__content">
    <h4>No hay convenios activos</h4>
    <p>Registra un convenio, sube el archivo firmado y marca su estatus como <strong>Activa</strong> para habilitar el proceso de residencia y la activaci&oacute;n de la empresa.</p>
    <?php if (empty($empresaIsReadOnly ?? false)): ?>
      <a href="<?php echo htmlspecialchars($nuevoConvenioUrl ?? '#', ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">➕ Registrar convenio</a>
      <?php if (!empty($empresaEditUrl ?? '')): ?>
        <a href="<?php echo htmlspecialchars($empresaEditUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary" style="margin-top:8px;">✏️ Editar empresa</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
