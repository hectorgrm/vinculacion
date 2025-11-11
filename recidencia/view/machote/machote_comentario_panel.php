<?php
/**
 * ===============================================================
 * Vista parcial: Panel de comentarios de Machote (Hijo)
 * ---------------------------------------------------------------
 * Muestra y gestiona comentarios de la tabla rp_machote_comentario
 * asociados a un machote hijo (rp_convenio_machote.id).
 *
 * Requiere:
 *   - $machoteId (int)             → ID del machote hijo (obligatorio)
 *   - $currentUserId (int|null)    → ID del usuario autenticado (opcional; si no viene, intenta leer de $_SESSION)
 *
 * Endpoints usados (handler):
 *   - GET  machote_comentario_handler.php?action=listar&machote_id={id}
 *   - GET  machote_comentario_handler.php?action=contar&machote_id={id}
 *   - POST machote_comentario_handler.php (action=agregar, machote_id, usuario_id, clausula, comentario)
 *   - POST machote_comentario_handler.php (action=resuelto, comentario_id)
 *   - POST machote_comentario_handler.php (action=eliminar, comentario_id)
 * 
 * Para incluirlo dentro de `convenio_view.php` (ya con tus CSS cargados):
 *   <?php
 *     // Asegúrate de tener $machoteId (id del rp_convenio_machote hijo) disponible
 *     // y tu id de usuario en sesión (ajusta la clave según tu auth).
 *     $currentUserId = (int)($_SESSION['usuario']['id'] ?? $_SESSION['user']['id'] ?? 0);
 *     include __DIR__ . '/machote_comentario_panel.php';
 *   ?>
 * 
 * Si quieres abrirlo de forma independiente, también puedes acceder con:
 *   /recidencia/view/machote/machote_comentario_panel.php?machote_id=1
 * y asegurarte de incluir los CSS globales arriba del include, o envolver este
 * fragmento dentro de tu layout (sidebar, topbar) como ya lo haces.
 * ===============================================================
 */

declare(strict_types=1);

// 1) Resolver $machoteId desde variable previa o desde query string.
$machoteId = isset($machoteId) ? (int)$machoteId : (int)(filter_input(INPUT_GET, 'machote_id', FILTER_VALIDATE_INT) ?: 0);

// 2) Intentar tomar el usuario actual de sesión si no vino por variable.
if (!isset($currentUserId)) {
    $currentUserId = isset($_SESSION['usuario']['id'])
        ? (int) $_SESSION['usuario']['id']
        : ((int) ($_SESSION['user']['id'] ?? 0));
}

// 3) Bandera de permisos (ajústala cuando conectemos roles reales).
$canResolveAll = true;  // en el futuro: ($_SESSION['usuario']['rol'] ?? '') !== 'empresa';

// 4) ID único del panel (útil si incluyes varios en la misma pantalla)
$__mcPanelId = $__mcPanelId ?? ('mc-panel-' . ($machoteId ?: 'x'));
?>

<section id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>" class="card" style="margin-top:16px;">
  <header style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <div>
      <h3 style="margin:0">🗒️ Comentarios de revisión</h3>
      <p class="text-muted" style="margin:6px 0 0 0;">
        Agrega observaciones por cláusula. No es un chat; cada comentario se atiende y se marca como <strong>resuelto</strong> cuando quede corregido en el machote.
      </p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
      <div class="chip">Pendientes: <span id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-count-pend">0</span></div>
      <div class="chip alt">Resueltos: <span id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-count-res">0</span></div>
    </div>
  </header>

  <div class="content" style="display:grid;gap:18px;">
    <!-- Filtros + Formulario en una misma fila -->
    <div class="grid" style="grid-template-columns: 1fr; gap:16px;">
      <!-- Filtro simple por estatus (cliente/administrador) -->
      <div class="field" style="max-width:280px;">
        <label for="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-filtro">Ver comentarios</label>
        <select id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-filtro">
          <option value="todos">Todos</option>
          <option value="pendiente" selected>Pendientes</option>
          <option value="resuelto">Resueltos</option>
        </select>
      </div>

      <!-- Formulario agregar comentario -->
      <form id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-form" class="form" style="margin:0;">
        <input type="hidden" name="action" value="agregar">
        <input type="hidden" name="machote_id" value="<?= (int)$machoteId ?>">
        <input type="hidden" name="usuario_id" value="<?= (int)$currentUserId ?>">

        <div class="grid">
          <div class="field">
            <label for="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-clausula">Cláusula / Sección (opcional)</label>
            <input type="text" id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-clausula" name="clausula" placeholder="Ej. 'Cláusula Segunda', 'Objeto', 'Vigencia'">
          </div>

          <div class="field col-span-2">
            <label for="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-comentario" class="required">Comentario *</label>
            <textarea id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-comentario" name="comentario" rows="3" placeholder="Describe claramente qué se debe ajustar o corregir..."></textarea>
          </div>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:flex-end">
          <button type="reset" class="btn">Limpiar</button>
          <button type="submit" class="btn primary">➕ Agregar comentario</button>
        </div>
      </form>
    </div>

    <hr class="divider"/>

    <!-- Lista de comentarios -->
    <div id="<?= htmlspecialchars($__mcPanelId, ENT_QUOTES, 'UTF-8') ?>-lista" style="display:flex;flex-direction:column;gap:12px;">
      <!-- Render dinámico por JS -->
      <div class="text-muted">Cargando comentarios…</div>
    </div>
  </div>
</section>

<style>
  /* ===== Estilos específicos del panel de comentarios (ligeros) ===== */
  #<?= $__mcPanelId ?> .divider{border:none;border-top:1px solid #e2e8f0;margin:8px 0;}
  #<?= $__mcPanelId ?> .mc-item{
    border:1px solid #e2e8f0;
    border-radius:10px;
    background:#ffffff;
    padding:14px 16px;
  }
  #<?= $__mcPanelId ?> .mc-header{
    display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:6px;
  }
  #<?= $__mcPanelId ?> .mc-title{font-weight:700;color:#0f172a;}
  #<?= $__mcPanelId ?> .mc-meta{font-size:.85rem;color:#64748b;margin-top:4px;}
  #<?= $__mcPanelId ?> .mc-actions{display:flex;gap:8px;margin-top:10px;}
  #<?= $__mcPanelId ?> .badge{padding:2px 10px;border-radius:999px;font-weight:700;font-size:.8rem;}
  #<?= $__mcPanelId ?> .badge.pendiente{background:#fef3c7;color:#92400e;}
  #<?= $__mcPanelId ?> .badge.resuelto{background:#dcfce7;color:#166534;}
  #<?= $__mcPanelId ?> .empty{
    padding:18px;border:1px dashed #cbd5e1;border-radius:10px;color:#64748b;text-align:center;
    background:#f8fafc;
  }
  /* Toast minimalista */
  #<?= $__mcPanelId ?> .toast{
    position:fixed; right:16px; bottom:16px; z-index:9999;
    background:#0d9488; color:#fff; padding:10px 14px; border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,.15);
    display:none; font-weight:600;
  }
  #<?= $__mcPanelId ?> .toast.error{ background:#dc2626; }
</style>

<script>
(() => {
  const PANEL_ID   = <?= json_encode($__mcPanelId) ?>;
  const MACHOTE_ID = <?= (int)$machoteId ?>;
  const CURRENT_USER_ID = <?= (int)$currentUserId ?>;
  const CAN_RESOLVE = <?= $canResolveAll ? 'true' : 'false' ?>;

  const HANDLER_URL = '../../gpt_guardado/../handler/machote/machote_comentario_handler.php'; // ruta relativa desde /view/machote/

  const $root        = document.getElementById(PANEL_ID);
  const $lista       = $root.querySelector(`#${CSS.escape(PANEL_ID)}-lista`);
  const $filtro      = $root.querySelector(`#${CSS.escape(PANEL_ID)}-filtro`);
  const $form        = $root.querySelector(`#${CSS.escape(PANEL_ID)}-form`);
  const $clausulaInp = $root.querySelector(`#${CSS.escape(PANEL_ID)}-clausula`);
  const $comentInp   = $root.querySelector(`#${CSS.escape(PANEL_ID)}-comentario`);
  const $countPend   = $root.querySelector(`#${CSS.escape(PANEL_ID)}-count-pend`);
  const $countRes    = $root.querySelector(`#${CSS.escape(PANEL_ID)}-count-res`);

  // --- Toast helper ---
  const $toast = document.createElement('div');
  $toast.className = 'toast';
  $root.appendChild($toast);

  function showToast(msg, isError=false){
    $toast.textContent = msg;
    $toast.classList.toggle('error', !!isError);
    $toast.style.display = 'block';
    setTimeout(() => { $toast.style.display = 'none'; }, 2200);
  }

  // --- Render de lista ---
  function renderLista(items){
    const filtro = $filtro.value; // 'todos' | 'pendiente' | 'resuelto'
    const frag = document.createDocumentFragment();
    let pend = 0, res = 0;

    const filtered = items.filter(it => {
      if (it.estatus === 'pendiente') pend++;
      if (it.estatus === 'resuelto')  res++;
      return (filtro === 'todos') || it.estatus === filtro;
    });

    $countPend.textContent = String(pend);
    $countRes.textContent  = String(res);

    if (filtered.length === 0){
      $lista.innerHTML = `<div class="empty">No hay comentarios en “${filtro}”.</div>`;
      return;
    }

    filtered.forEach(item => {
      const el = document.createElement('div');
      el.className = 'mc-item';
      el.dataset.id = item.id;
      el.dataset.estatus = item.estatus;

      const badgeClass = item.estatus === 'resuelto' ? 'resuelto' : 'pendiente';
      const usuario = item.usuario_nombre ? item.usuario_nombre : (item.usuario_id ? `Usuario #${item.usuario_id}` : '—');
      const fecha   = item.creado_en ?? '';

      el.innerHTML = `
        <div class="mc-header">
          <div class="mc-title">
            ${item.clausula ? `<span>${escapeHtml(item.clausula)}</span>` : '<span>Comentario</span>'}
          </div>
          <div class="mc-actions">
            ${ item.estatus === 'pendiente' && CANI('resolve') ? `<button class="btn primary" data-action="resuelto">✔️ Marcar resuelto</button>` : '' }
            ${ CANI('delete', item) ? `<button class="btn btn-outline" data-action="eliminar">🗑️ Eliminar</button>` : '' }
          </div>
        </div>
        <div class="mc-body">
          <p style="margin:6px 0 0 0;">${escapeHtml(item.comentario)}</p>
        </div>
        <div class="mc-meta">
          <span>Estado: <span class="badge ${badgeClass}">${item.estatus}</span></span>
          · <span>Autor: ${escapeHtml(usuario)}</span>
          · <span>${escapeHtml(fecha)}</span>
        </div>
      `;
      frag.appendChild(el);
    });

    $lista.innerHTML = '';
    $lista.appendChild(frag);
  }

  function escapeHtml(str){
    return String(str ?? '').replace(/[&<>"'`=\/]/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','`':'&#96;','/':'&#x2F;'
    }[s] || s));
  }

  // Reglas de UI según permisos (ajusta cuando conectes roles reales)
  function CANI(action /* 'resolve' | 'delete' */, item){
    if (CANI.cache[action] !== undefined) return CANI.cache[action];
    if (action === 'resolve') return CANI.cache[action] = !!CANI_RESOLVE;
    if (action === 'delete')  return CANI.cache[action] = !!CANI_RESOLVE || (item && Number(item.usuario_id) === Number(CURRENT_USER_ID));
    return false;
  }
  CANI.cache = {};

  // --- Carga de comentarios y contadores ---
  async function loadComentarios(){
    try {
      const resp = await fetch(`${HANDLER_URL}?action=listar&machote_id=${encodeURIComponent(MACHOTE_ID)}`);
      const data = await resp.json();
      if (!data.success) {
        renderError(data.error || 'No se pudieron obtener los comentarios.');
        return;
      }
      renderLista(Array.isArray(data.data) ? data.data : []);
    } catch (e){
      renderError('Error de red al cargar comentarios.');
      console.error(e);
    }
  }

  async function refreshCounters(){
    try {
      const resp = await fetch(`${HANDLER_URL}?action=contar&machote_id=${encodeURIComponent(MACHOTE_ID)}`);
      const data = await resp.json();
      if (data && data.success){
        $countPend.textContent = String(data.total ?? 0);
      }
    } catch (e) {
      // silencioso
    }
  }

  function renderError(msg){
    $lista.innerHTML = `<div class="empty">⚠️ ${escapeHtml(msg)}</div>`;
  }

  // --- Submit nuevo comentario ---
  $form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new URLSearchParams();
    formData.set('action', 'agregar');
    formData.set('machote_id', String(MACHOTE_ID));
    formData.set('usuario_id', String(<?= (int)$currentUserId ?>));
    formData.set('clausula', ($clausulaInp.value || '').trim());
    formData.set('comentario', ($comentInp.value || '').trim());

    if (!formData.get('comentario')) {
      showToast('Escribe el comentario 🙌', true);
      $comentInp.focus();
      return;
    }

    try {
      const resp = await fetch(HANDLER_URL, { method:'POST', body: formData });
      const data = await resp.json();
      if (data?.success){
        $form.reset();
        showToast('Comentario agregado ✅');
        await loadComentarios();
      } else {
        showToast(data?.error || 'No fue posible guardar.', true);
      }
    } catch (err){
      console.error(err);
      showToast('Error de red al guardar.', true);
    }
  });

  // --- Acciones de cada item (resolver / eliminar) ---
  $lista.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;

    const itemEl = btn.closest('.mc-item');
    const id = itemEl?.dataset?.id;
    if (!id) return;

    const action = btn.dataset.action;
    if (action === 'eliminar') {
      const ok = confirm('¿Eliminar este comentario? Esta acción no se puede deshacer.');
      if (!ok) return;
    }

    const formData = new URLSearchParams();
    formData.set('action', action);
    formData.set('comentario_id', id);

    try {
      const resp = await fetch(HANDLER_URL, { method:'POST', body: formData });
      const data = await resp.json();
      if (data?.success){
        showToast((action === 'resuelto') ? 'Marcado como resuelto ✅' : 'Eliminado 🗑️');
        await loadComentarios();
      } else {
        showToast(data?.error || 'No se pudo procesar la acción.', true);
      }
    } catch (err){
      console.error(err);
      showToast('Error de red.', true);
    }
  });

  // --- Filtro por estatus ---
  $filtro.addEventListener('change', () => {
    // Re-render usando la última data en memoria
    loadComentarios();
  });

  // Carga inicial
  if (!MACHOTE_ID) {
    $root.querySelector('.content').innerHTML =
      '<div class="empty">No se recibió <code>machote_id</code>. Pásalo a este panel antes de incluirlo.</div>';
  } else {
    loadComentarios();
    // Además actualiza contadores cada 30s por si hay comentarios nuevos desde el otro lado
    setInterval(() => {
      refreshCounters();
      // reaplicar filtro y refrescar lista por si cambió estatus
      loadComentarios();
    }, 30000);
  }
})();
</script>
