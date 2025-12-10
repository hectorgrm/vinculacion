<div class="alert alert-info" style="margin: 0 0 16px;">
  <h4 style="margin:0 0 6px 0;">¿Cómo activar esta empresa?</h4>
  <p style="margin:0 0 8px 0;">Necesitas al menos un convenio en estatus <strong>Activa</strong>.</p>
  <ol style="margin:0 0 12px 18px; padding-left:6px;">
    <li>Registra un convenio y m&aacute;rcalo como <strong>Activa</strong>.</li>
    <li>Luego ve a <strong> Empresa</strong> y selecciona estatus <strong>Activa</strong>.</li>
  </ol>
  <div class="actions" style="margin-top:8px; gap:8px; padding:0; justify-content:flex-start;">
    <?php if (!empty($nuevoConvenioUrl ?? '')): ?>
      <a class="btn primary" href="<?php echo htmlspecialchars($nuevoConvenioUrl, ENT_QUOTES, 'UTF-8'); ?>">Registrar convenio</a>
    <?php endif; ?>
    <?php if (!empty($empresaEditUrl ?? '')): ?>
      <a class="btn secondary" href="<?php echo htmlspecialchars($empresaEditUrl, ENT_QUOTES, 'UTF-8'); ?>">Editar empresa</a>
    <?php endif; ?>
  </div>
</div>
