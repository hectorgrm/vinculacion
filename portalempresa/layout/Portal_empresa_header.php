<!-- 🌐 Portal Empresa Header -->
<style>
  .portal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 16px 32px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    border-bottom: 3px solid #004aad;
  }

  .portal-header .brand {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .portal-header .brand img {
    width: 65px;
    height: 65px;
    border-radius: 12px;
    object-fit: contain;
    border: 1px solid #ddd;
    background: #fafafa;
    padding: 6px;
  }

  .portal-header .brand-info {
    display: flex;
    flex-direction: column;
  }

  .portal-header .brand-info strong {
    font-size: 1.1rem;
    color: #111827;
  }

  .portal-header .brand-info small {
    color: #64748b;
    font-size: 0.85rem;
  }

  .portal-header .userbox {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .portal-header .userbox .company {
    font-weight: 600;
    color: #004aad;
  }

  .portal-header .btn {
    text-decoration: none;
    color: #004aad;
    font-weight: 600;
    border: 1px solid #004aad;
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
  }

  .portal-header .btn:hover {
    background: #004aad;
    color: #fff;
  }

  @media (max-width: 768px) {
    .portal-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
      padding: 16px;
    }

    .portal-header .brand img {
      width: 55px;
      height: 55px;
    }
  }
</style>

<header class="portal-header">
  <div class="brand">
    <!-- 🏢 Logotipo de empresa -->
    <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg"
         alt="Logo de la empresa" />
    <div class="brand-info">
      <strong>Barbería Gómez</strong>
      <small>Portal de Empresa · Residencias Profesionales</small>
    </div>
  </div>

  <div class="userbox">
    <span class="company">Barbería Gómez</span>
    <a href="portal_index.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>
