<?php
// extraemos del ViewModel
$empresaLogoUrl = $portalViewModel['empresaLogoUrl'] ?? 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
$empresaNombre  = $portalViewModel['empresaNombre'] ?? 'Empresa';
?>
  
  
  <link rel="stylesheet" href="../assets/css/header.css">

<header class="portal-header">
  <div class="brand">
    <!-- 🏢 Logotipo dinámico -->
    <img src="<?= htmlspecialchars($empresaLogoUrl) ?>" alt="Logo de la empresa" />

    <div class="brand-info">
      <strong><?= htmlspecialchars($empresaNombre) ?></strong>
      <small>Portal de Empresa · Residencias Profesionales</small>
    </div>
  </div>

  <div class="userbox">
    <span class="company"><?= htmlspecialchars($empresaNombre) ?></span>
    <a href="portal_index.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>