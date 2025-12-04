<?php

declare(strict_types=1);

/**
 * Normaliza los indicadores generales del machote.
 */
function machoteViewNormalizeStats(?array $stats): array
{
    $defaults = [
        'total' => 0,
        'pendientes' => 0,
        'resueltos' => 0,
        'progreso' => 0,
        'estado' => 'En revision',
    ];

    return is_array($stats) ? array_merge($defaults, $stats) : $defaults;
}

/**
 * Normaliza los permisos que controlan acciones en la vista.
 */
function machoteViewNormalizePermisos(?array $permisos): array
{
    $defaults = ['puede_comentar' => false, 'puede_confirmar' => false];

    return is_array($permisos) ? array_merge($defaults, $permisos) : $defaults;
}

/**
 * Normaliza la informacion del documento mostrado en la vista.
 */
function machoteViewNormalizeDocumento(?array $documento): array
{
    $defaults = [
        'has_html' => false,
        'html' => '',
        'has_pdf' => false,
        'pdf_url' => null,
        'pdf_embed_url' => null,
        'fuente' => null,
    ];

    return is_array($documento) ? array_merge($defaults, $documento) : $defaults;
}

/**
 * Construye los mensajes flash a partir de los parametros de consulta.
 */
function machoteViewBuildFlashMessages(array $queryParams): array
{
    $flashMessages = [];

    $statusCode = (string) ($queryParams['comentario_status'] ?? '');
    $comentarioStatusMap = [
        'added' => 'Comentario registrado correctamente.',
    ];
    if ($statusCode !== '' && isset($comentarioStatusMap[$statusCode])) {
        $flashMessages[] = ['type' => 'success', 'text' => $comentarioStatusMap[$statusCode]];
    }

    $comentarioError = (string) ($queryParams['comentario_error'] ?? '');
    $comentarioErrorMap = [
        'invalid' => 'Completa todos los campos del comentario.',
        'internal' => 'No se pudo guardar tu comentario. Intenta mas tarde.',
        'session' => 'Inicia sesion nuevamente para continuar.',
        'readonly' => 'El portal esta en modo lectura; no puedes publicar comentarios.',
    ];
    if ($comentarioError !== '' && isset($comentarioErrorMap[$comentarioError])) {
        $flashMessages[] = ['type' => 'error', 'text' => $comentarioErrorMap[$comentarioError]];
    }

    $confirmStatus = (string) ($queryParams['confirm_status'] ?? '');
    $confirmStatusMap = [
        'confirmed' => 'Gracias. Tu confirmacion fue registrada.',
        'already' => 'Este documento ya habia sido confirmado previamente.',
    ];
    if ($confirmStatus !== '' && isset($confirmStatusMap[$confirmStatus])) {
        $flashMessages[] = ['type' => 'success', 'text' => $confirmStatusMap[$confirmStatus]];
    }

    $confirmError = (string) ($queryParams['confirm_error'] ?? '');
    $confirmErrorMap = [
        'invalid' => 'No fue posible identificar el documento a confirmar.',
        'session' => 'Tu sesion expiro. Inicia sesion nuevamente.',
        'pending' => 'Aun quedan comentarios pendientes por resolver.',
        'readonly' => 'La confirmacion esta bloqueada porque el portal esta en modo solo lectura.',
        'internal' => 'Ocurrio un problema al registrar la confirmacion.',
    ];
    if ($confirmError !== '' && isset($confirmErrorMap[$confirmError])) {
        $flashMessages[] = ['type' => 'error', 'text' => $confirmErrorMap[$confirmError]];
    }

    return $flashMessages;
}

/**
 * Renderiza un mensaje de hilo de comentarios, incluyendo respuestas anidadas.
 */
function machoteViewRenderThreadMessage(array $mensaje, string $uploadsBasePath): void
{
    $autorRol = (string) ($mensaje['autor_rol'] ?? 'empresa');
    $rolLabel = ucfirst($autorRol);
    $autorNombre = trim((string) ($mensaje['autor_nombre'] ?? $rolLabel));
    $mostrarNombre = strcasecmp($autorNombre, $rolLabel) !== 0;
    $fecha = (string) ($mensaje['creado_en'] ?? '');
    $comentario = (string) ($mensaje['comentario'] ?? '');
    $archivoPath = $mensaje['archivo_path'] ?? null;
    $archivoHref = $archivoPath !== null && $archivoPath !== ''
        ? rtrim($uploadsBasePath, '/') . '/' . ltrim((string) $archivoPath, '/\\')
        : null;
    ?>
    <div class="message">
      <div class="head">
        <span class="pill <?= htmlspecialchars($autorRol) ?>"><?= htmlspecialchars($rolLabel) ?></span>
        <?php if ($mostrarNombre): ?>
          <strong><?= htmlspecialchars($autorNombre) ?></strong>
        <?php endif; ?>
        <?php if ($fecha !== ''): ?>
          <time><?= htmlspecialchars($fecha) ?></time>
        <?php endif; ?>
      </div>
      <p><?= nl2br(htmlspecialchars($comentario)) ?></p>
      <?php if ($archivoHref !== null): ?>
        <div class="files">
          <a href="<?= htmlspecialchars($archivoHref) ?>" target="_blank" rel="noopener">Ver archivo</a>
        </div>
      <?php endif; ?>
    </div>
    <?php if (!empty($mensaje['respuestas']) && is_array($mensaje['respuestas'])): ?>
      <div class="messages nested">
        <?php foreach ($mensaje['respuestas'] as $respuesta): ?>
          <?php machoteViewRenderThreadMessage($respuesta, $uploadsBasePath); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php
}
