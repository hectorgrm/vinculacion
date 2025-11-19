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

        if (!is_array($conveniosActivos)) {
            $conveniosActivos = [];
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
        }

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
        $empresaProgresoUrl = 'empresa_progreso.php';
        $empresaEditUrl = 'empresa_edit.php';
        $empresaDeleteUrl = 'empresa_delete.php';

        if ($empresaIdQuery !== '') {
            $nuevoConvenioUrl .= '?empresa=' . urlencode($empresaIdQuery);
            $empresaProgresoUrl .= '?id_empresa=' . urlencode($empresaIdQuery);
            $empresaEditUrl .= '?id=' . urlencode($empresaIdQuery);
            $empresaDeleteUrl .= '?id=' . urlencode($empresaIdQuery);
        }

        return [
            'empresaId' => $empresaId,
            'empresa' => $empresa,
            'controllerError' => $controllerError,
            'notFoundMessage' => $notFoundMessage,
            'inputError' => $inputError,
            'successMessage' => $successMessage,
            'conveniosActivos' => $conveniosActivos,
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
            'empresaProgresoUrl' => $empresaProgresoUrl,
            'empresaEditUrl' => $empresaEditUrl,
            'empresaDeleteUrl' => $empresaDeleteUrl,
        ];
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
