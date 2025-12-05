<?php

declare(strict_types=1);

require_once __DIR__ . '/../../functions/empresa/empresa_functions_view.php';

if (!function_exists('empresaViewBuildHeaderData')) {
    /**
     * @param array<string, mixed> $handlerResult
     * @return array<string, mixed>
     */
    function empresaViewBuildHeaderData(array $handlerResult): array
    {
        $empresaId = $handlerResult['empresaId'] ?? null;
        $empresa = $handlerResult['empresa'] ?? null;
        $controllerError = $handlerResult['controllerError'] ?? null;
        $notFoundMessage = $handlerResult['notFoundMessage'] ?? null;
        $inputError = $handlerResult['inputError'] ?? null;
        $successMessage = $handlerResult['successMessage'] ?? null;
        $conveniosActivos = $handlerResult['conveniosActivos'] ?? [];
        $conveniosArchivados = $handlerResult['conveniosArchivados'] ?? [];
        $estudiantes = $handlerResult['estudiantes'] ?? [];

        if (!is_array($estudiantes)) {
            $estudiantes = [];
        }

        if (!is_array($conveniosActivos)) {
            $conveniosActivos = [];
        }

        if (!is_array($conveniosArchivados)) {
            $conveniosArchivados = [];
        }

        $nombre = 'Sin datos';
        $rfc = 'N/A';
        $representante = 'No especificado';
        $telefono = 'No registrado';
        $correo = 'No registrado';
        $estatusClass = 'badge secondary';
        $estatusLabel = 'Sin estatus';
        $creadoEn = 'N/A';
        $actualizadoEn = 'Sin actualizar';
        $numeroControl = '';
        $logoUrl = null;
        $empresaIsEnRevision = false;
        $empresaIsActiva = false;
        $empresaIsCompletada = false;
        $empresaIsInactiva = false;
        $empresaTieneConvenioActivo = false;

        if (is_array($empresa)) {
            $nombre = (string) ($empresa['nombre_label'] ?? ($empresa['nombre'] ?? $nombre));
            $rfc = (string) ($empresa['rfc_label'] ?? $rfc);
            $representante = (string) ($empresa['representante_label'] ?? $representante);
            $telefono = (string) ($empresa['telefono_label'] ?? $telefono);
            $correo = (string) ($empresa['correo_label'] ?? $correo);
            $estatusClass = (string) ($empresa['estatus_badge_class'] ?? $estatusClass);
            $estatusLabel = (string) ($empresa['estatus_badge_label'] ?? $estatusLabel);
            $creadoEn = (string) ($empresa['creado_en_label'] ?? $creadoEn);
            $actualizadoEn = (string) ($empresa['actualizado_en_label'] ?? $actualizadoEn);
            $numeroControl = isset($empresa['numero_control']) ? (string) $empresa['numero_control'] : '';
            $logoUrl = isset($empresa['logo_url']) && $empresa['logo_url'] !== null
                ? (string) $empresa['logo_url']
                : null;
            $empresaIsEnRevision = empresaViewIsStatusRevision($empresa['estatus'] ?? null);
            $empresaIsActiva = empresaViewIsStatusActiva($empresa['estatus'] ?? null);
            $empresaIsCompletada = empresaViewIsStatusCompletada($empresa['estatus'] ?? null);
            $empresaIsInactiva = empresaViewIsStatusInactiva($empresa['estatus'] ?? null);
        }

        $empresaTieneConvenioActivo = empresaViewHasConvenioActivo($conveniosActivos);

        $empresaSubtitulo = $numeroControl !== ''
            ? 'Número de control: ' . $numeroControl
            : 'RFC: ' . $rfc;
        $logoAltText = 'Logotipo de ' . $nombre;
        $logoUploadAction = '../../handler/empresa/empresa_logo_upload_handler.php';
        $logoBaseUrl = '../../';
        $canUploadLogo = $empresaId !== null
            && $controllerError === null
            && $inputError === null
            && $notFoundMessage === null
            && is_array($empresa);

        $empresaIdQuery = $empresaId !== null ? (string) $empresaId : '';
        $nuevoConvenioUrl = '../convenio/convenio_add.php';
        $nuevoEstudianteUrl = '../estudiante/estudiante_add.php';
        $empresaProgresoUrl = 'empresa_progreso.php';
        $empresaEditUrl = 'empresa_edit.php';
        $empresaDeleteUrl = 'empresa_delete.php';

        if ($empresaIdQuery !== '') {
            $nuevoConvenioUrl .= '?empresa=' . urlencode($empresaIdQuery);
            $empresaProgresoUrl .= '?id_empresa=' . urlencode($empresaIdQuery);
            $empresaEditUrl .= '?id=' . urlencode($empresaIdQuery);
            $empresaDeleteUrl .= '?id=' . urlencode($empresaIdQuery);

            $query = ['empresa' => $empresaIdQuery];
            $defaultConvenioId = empresaViewDefaultConvenioId($conveniosActivos);

            if ($defaultConvenioId !== null) {
                $query['convenio'] = (string) $defaultConvenioId;
            }

            $nuevoEstudianteUrl .= '?' . http_build_query($query);
        }

        return [
            'empresaId' => $empresaId,
            'empresa' => $empresa,
            'controllerError' => $controllerError,
            'notFoundMessage' => $notFoundMessage,
            'inputError' => $inputError,
            'successMessage' => $successMessage,
            'conveniosActivos' => $conveniosActivos,
            'conveniosArchivados' => $conveniosArchivados,
            'nombre' => $nombre,
            'rfc' => $rfc,
            'representante' => $representante,
            'telefono' => $telefono,
            'correo' => $correo,
            'estatusClass' => $estatusClass,
            'estatusLabel' => $estatusLabel,
            'creadoEn' => $creadoEn,
            'actualizadoEn' => $actualizadoEn,
            'numeroControl' => $numeroControl,
            'logoUrl' => $logoUrl,
            'empresaSubtitulo' => $empresaSubtitulo,
            'logoAltText' => $logoAltText,
            'logoUploadAction' => $logoUploadAction,
            'logoBaseUrl' => $logoBaseUrl,
            'canUploadLogo' => $canUploadLogo,
            'empresaIdQuery' => $empresaIdQuery,
            'nuevoConvenioUrl' => $nuevoConvenioUrl,
            'nuevoEstudianteUrl' => $nuevoEstudianteUrl,
            'empresaProgresoUrl' => $empresaProgresoUrl,
            'empresaEditUrl' => $empresaEditUrl,
            'empresaDeleteUrl' => $empresaDeleteUrl,
            'empresaIsEnRevision' => $empresaIsEnRevision,
            'empresaIsActiva' => $empresaIsActiva,
            'empresaIsCompletada' => $empresaIsCompletada,
            'empresaIsInactiva' => $empresaIsInactiva,
            'empresaIsReadOnly' => $empresaIsCompletada || $empresaIsInactiva,
            'empresaTieneConvenioActivo' => $empresaTieneConvenioActivo,
            'estudiantes' => $estudiantes,
        ];
    }
}

if (!function_exists('empresaViewSanitizeStatusForComparison')) {
    function empresaViewSanitizeStatusForComparison(string $value): string
    {
        $normalized = $value;

        if (function_exists('iconv')) {
            $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);

            if ($transliterated !== false) {
                $normalized = $transliterated;
            }
        }

        $lower = function_exists('mb_strtolower') ? mb_strtolower($normalized, 'UTF-8') : strtolower($normalized);
        $withoutAccents = strtr($lower, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n',
        ]);
        $clean = preg_replace('/[^a-z]/', '', $withoutAccents);

        return $clean ?? '';
    }
}

if (!function_exists('empresaViewIsStatusRevision')) {
    function empresaViewIsStatusRevision(?string $estatus): bool
    {
        $normalizedStatus = empresaNormalizeStatus($estatus);
        $clean = empresaViewSanitizeStatusForComparison($normalizedStatus);

        return $clean !== '' && strpos($clean, 'revision') !== false;
    }
}

if (!function_exists('empresaViewIsStatusActiva')) {
    function empresaViewIsStatusActiva(?string $estatus): bool
    {
        return empresaNormalizeStatus($estatus) === 'Activa';
    }
}

if (!function_exists('empresaViewIsStatusCompletada')) {
    function empresaViewIsStatusCompletada(?string $estatus): bool
    {
        return empresaNormalizeStatus($estatus) === 'Completada';
    }
}

if (!function_exists('empresaViewIsStatusInactiva')) {
    function empresaViewIsStatusInactiva(?string $estatus): bool
    {
        return empresaNormalizeStatus($estatus) === 'Inactiva';
    }
}

if (!function_exists('empresaViewIsConvenioBloqueanteStatus')) {
    function empresaViewIsConvenioBloqueanteStatus(?string $estatus): bool
    {
        $clean = empresaViewSanitizeStatusForComparison(empresaNormalizeStatus($estatus));
        $clean = str_replace('a3', 'o', $clean);

        if (strpos($clean, 'enrevisi') === 0) {
            $clean = 'enrevision';
        }

        return in_array($clean, ['activa', 'enrevision', 'suspendida'], true);
    }
}

if (!function_exists('empresaViewHasConvenioActivo')) {
    /**
     * @param array<int, array<string, mixed>> $conveniosActivos
     */
    function empresaViewHasConvenioActivo(array $conveniosActivos): bool
    {
        foreach ($conveniosActivos as $convenio) {
            if (!is_array($convenio)) {
                continue;
            }

            if (empresaViewIsConvenioBloqueanteStatus($convenio['estatus'] ?? null)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('empresaViewBuildDocumentosData')) {
    /**
     * @param array<string, mixed> $handlerResult
     * @return array<string, mixed>
     */
    function empresaViewBuildDocumentosData(array $handlerResult): array
    {
        $documentos = $handlerResult['documentos'] ?? [];
        $documentosStats = $handlerResult['documentosStats'] ?? [];
        $documentosGestionUrl = $handlerResult['documentosGestionUrl'] ?? null;

        if (!is_array($documentos)) {
            $documentos = [];
        }

        if (!is_array($documentosStats)) {
            $documentosStats = [
                'total' => 0,
                'subidos' => 0,
                'aprobados' => 0,
                'porcentaje' => 0,
            ];
        } else {
            $documentosStats = [
                'total' => isset($documentosStats['total']) ? (int) $documentosStats['total'] : 0,
                'subidos' => isset($documentosStats['subidos']) ? (int) $documentosStats['subidos'] : 0,
                'aprobados' => isset($documentosStats['aprobados']) ? (int) $documentosStats['aprobados'] : 0,
                'porcentaje' => isset($documentosStats['porcentaje']) ? (int) $documentosStats['porcentaje'] : 0,
            ];
        }

        if (!is_string($documentosGestionUrl) || $documentosGestionUrl === '') {
            $documentosGestionUrl = '../empresadocumentotipo/empresa_documentotipo_list.php';
        }

        return [
            'documentos' => $documentos,
            'documentosStats' => $documentosStats,
            'documentosGestionUrl' => $documentosGestionUrl,
            'docsTotal' => $documentosStats['total'],
            'docsSubidos' => $documentosStats['subidos'],
            'docsAprobados' => $documentosStats['aprobados'],
            'progreso' => $documentosStats['porcentaje'],
        ];
    }
}

if (!function_exists('empresaViewBuildMachoteData')) {
    /**
     * @param mixed $machoteData
     * @return array<string, mixed>|null
     */
    function empresaViewBuildMachoteData(mixed $machoteData): ?array
    {
        if (!is_array($machoteData) || !isset($machoteData['id'])) {
            return null;
        }

        $estado = (string) ($machoteData['estado'] ?? 'Pendiente');
        $machoteEstadoClass = strtolower(str_replace([' ', '-'], '_', $estado));
        $machoteEstadoClass = preg_replace('/[^a-z0-9_]/', '', $machoteEstadoClass) ?: 'pendiente';

        return [
            'id' => (int) $machoteData['id'],
            'pendientes' => (int) ($machoteData['pendientes'] ?? 0),
            'resueltos' => (int) ($machoteData['resueltos'] ?? 0),
            'progreso' => (int) ($machoteData['progreso'] ?? 0),
            'estado' => $estado !== '' ? $estado : 'Pendiente',
            'estado_class' => $machoteEstadoClass,
        ];
    }
}

if (!function_exists('empresaViewDecorateAuditoriaItems')) {
    /**
     * @param array<int, array<string, mixed>> $items
     * @param int $visibleLimit
     * @return array{items: array<int, array<string, mixed>>, hasOverflow: bool, visibleLimit: int}
     */
    function empresaViewDecorateAuditoriaItems(array $items, int $visibleLimit = 5): array
    {
        $decorated = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $fecha = trim((string) ($item['fecha'] ?? '—'));
            $mensaje = trim((string) ($item['mensaje'] ?? 'Acción registrada'));
            $actorLabel = isset($item['actor_label']) && is_string($item['actor_label'])
                ? trim($item['actor_label'])
                : '';
            $actorNombre = isset($item['actor_nombre']) && is_string($item['actor_nombre'])
                ? trim($item['actor_nombre'])
                : '';
            $tipo = isset($item['actor_tipo']) ? (string) $item['actor_tipo'] : null;
            $detalleItems = [];

            if (isset($item['detalles']) && is_array($item['detalles'])) {
                foreach ($item['detalles'] as $detalle) {
                    if (!is_array($detalle)) {
                        continue;
                    }

                    $etiqueta = isset($detalle['campo_label']) && is_string($detalle['campo_label'])
                        ? trim($detalle['campo_label'])
                        : '';

                    if ($etiqueta === '' && isset($detalle['campo'])) {
                        $etiqueta = trim((string) $detalle['campo']);
                    }

                    $antes = isset($detalle['valor_anterior']) && $detalle['valor_anterior'] !== null
                        ? trim((string) $detalle['valor_anterior'])
                        : '';
                    $despues = isset($detalle['valor_nuevo']) && $detalle['valor_nuevo'] !== null
                        ? trim((string) $detalle['valor_nuevo'])
                        : '';

                    $detalleItems[] = [
                        'label' => $etiqueta !== '' ? $etiqueta : 'Campo',
                        'antes' => $antes !== '' ? $antes : '—',
                        'despues' => $despues !== '' ? $despues : '—',
                    ];
                }
            }

            if ($actorLabel === '' && $actorNombre !== '') {
                $actorLabel = $actorNombre;
            }

            if ($actorLabel === '') {
                if ($tipo === 'usuario') {
                    $actorLabel = 'Administrador / Usuario';
                } elseif ($tipo === 'empresa') {
                    $actorLabel = 'Empresa';
                } elseif ($tipo === 'sistema') {
                    $actorLabel = 'Sistema automático';
                }
            }

            if ($actorLabel === '') {
                $actorLabel = '—';
            }

            $decorated[] = [
                'fecha' => $fecha !== '' ? $fecha : '—',
                'mensaje' => $mensaje !== '' ? $mensaje : 'Acción registrada',
                'actor_label' => $actorLabel,
                'detalles' => $detalleItems,
            ];
        }

        return [
            'items' => $decorated,
            'hasOverflow' => count($decorated) > $visibleLimit,
            'visibleLimit' => $visibleLimit,
        ];
    }
}

if (!function_exists('empresaViewPortalStatusData')) {
    /**
     * @param array<string, mixed> $portalAccess
     * @return array{label: string, class: string}
     */
    function empresaViewPortalStatusData(array $portalAccess): array
    {
        $isActive = isset($portalAccess['activo']) && (int) $portalAccess['activo'] === 1;
        $expirationRaw = isset($portalAccess['expiracion']) ? trim((string) $portalAccess['expiracion']) : '';
        $isExpired = false;

        if ($expirationRaw !== '') {
            try {
                $expirationDate = new DateTimeImmutable($expirationRaw);
                $isExpired = $expirationDate <= new DateTimeImmutable('now');
            } catch (\Throwable) {
                $isExpired = false;
            }
        }

        if (!$isActive) {
            return ['label' => 'Inactivo', 'class' => 'badge badge-inactive'];
        }

        if ($isExpired) {
            return ['label' => 'Expirado', 'class' => 'badge badge-inactive'];
        }

        return ['label' => 'Activo', 'class' => 'badge badge-active'];
    }
}

if (!function_exists('empresaViewPortalAccessHelper')) {
    /**
     * @param array<string, mixed> $handlerResult
     * @return array<string, mixed>
     */
    function empresaViewPortalAccessHelper(array $handlerResult): array
    {
        $empresaId = $handlerResult['empresaId'] ?? null;
        $empresa = $handlerResult['empresa'] ?? null;
        $portalAccessRaw = $handlerResult['portalAccess'] ?? null;

        if (!is_int($empresaId)) {
            $empresaId = null;
        }

        $empresaIsActiva = false;
        $empresaIsEnRevision = false;

        if (is_array($empresa)) {
            $empresaIsActiva = empresaViewIsStatusActiva($empresa['estatus'] ?? null);
            $empresaIsEnRevision = empresaViewIsStatusRevision($empresa['estatus'] ?? null);
        }

        $empresaIdQuery = $empresaId !== null ? (string) $empresaId : '';
        $createUrl = '../portalacceso/portal_add.php';

        if ($empresaIdQuery !== '') {
            $createUrl .= '?empresa_id=' . urlencode($empresaIdQuery);
        }

        $record = null;
        $viewUrl = null;
        $editUrl = null;
        $deleteUrl = null;

        if ($empresaId !== null && is_array($portalAccessRaw)) {
            $recordEmpresaId = isset($portalAccessRaw['empresa_id']) ? (int) $portalAccessRaw['empresa_id'] : null;

            if ($recordEmpresaId === $empresaId && isset($portalAccessRaw['id'])) {
                $portalId = (int) $portalAccessRaw['id'];
                $statusData = empresaViewPortalStatusData($portalAccessRaw);

                $record = [
                    'id' => $portalId,
                    'token' => (string) ($portalAccessRaw['token'] ?? ''),
                    'nip' => isset($portalAccessRaw['nip']) && trim((string) $portalAccessRaw['nip']) !== ''
                        ? (string) $portalAccessRaw['nip']
                        : '—',
                    'estatus_label' => $statusData['label'],
                    'estatus_badge_class' => $statusData['class'],
                    'creado_en' => empresaViewFormatDateTime($portalAccessRaw['creado_en'] ?? null, 'Sin registrar'),
                ];

                $portalIdQuery = '?id=' . urlencode((string) $portalId);

                if ($empresaIdQuery !== '') {
                    $portalIdQuery .= '&empresa_id=' . urlencode($empresaIdQuery);
                }

                $viewUrl = '../portalacceso/portal_view.php' . $portalIdQuery;
                $editUrl = '../portalacceso/portal_edit.php' . $portalIdQuery;
                $deleteUrl = '../portalacceso/portal_delete.php' . $portalIdQuery;
            }
        }

        if ($record !== null) {
            $createUrl = null;
        }

        $actionsEnabled = ($empresaIsActiva || $empresaIsEnRevision) && $empresaId !== null;
        $disabledReason = null;

        if (!$actionsEnabled) {
            $disabledReason = (!$empresaIsActiva && !$empresaIsEnRevision)
                ? 'Activa o coloca en revisión la empresa para gestionar su acceso al portal.'
                : 'No se puede gestionar el portal sin un identificador de empresa válido.';
        }

        return [
            'portalAccess' => $record,
            'portalAccessCreateUrl' => $createUrl,
            'portalAccessViewUrl' => $viewUrl,
            'portalAccessEditUrl' => $editUrl,
            'portalAccessDeleteUrl' => $deleteUrl,
            'portalAccessActionsEnabled' => $actionsEnabled,
            'portalAccessDisabledReason' => $disabledReason,
        ];
    }
}
