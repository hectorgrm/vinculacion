<?php

declare(strict_types=1);

require_once __DIR__ . '/documentofunctions_list.php';

if (!function_exists('documentoViewDefaults')) {
    /**
     * @return array{
     *     documentId: ?int,
     *     document: ?array<string, mixed>,
     *     fileMeta: array{
     *         exists: bool,
     *         absolutePath: ?string,
     *         publicUrl: ?string,
     *         filename: ?string,
     *         sizeBytes: ?int,
     *         sizeLabel: ?string,
     *         extension: ?string,
     *         canPreview: bool
     *     },
     *     history: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function documentoViewDefaults(): array
    {
        return [
            'documentId' => null,
            'document' => null,
            'fileMeta' => [
                'exists' => false,
                'absolutePath' => null,
                'publicUrl' => null,
                'filename' => null,
                'sizeBytes' => null,
                'sizeLabel' => null,
                'extension' => null,
                'canPreview' => false,
            ],
            'history' => [],
            'controllerError' => null,
            'notFoundMessage' => null,
        ];
    }
}

if (!function_exists('documentoViewNormalizeId')) {
    function documentoViewNormalizeId(mixed $value): ?int
    {
        return documentoNormalizePositiveInt($value);
    }
}

if (!function_exists('documentoViewControllerErrorMessage')) {
    function documentoViewControllerErrorMessage(\Throwable $exception): string
    {
        $message = $exception->getMessage();
        $message = is_string($message) ? trim($message) : '';

        return $message !== ''
            ? $message
            : 'No se pudo obtener los datos del documento.';
    }
}

if (!function_exists('documentoViewNotFoundMessage')) {
    function documentoViewNotFoundMessage(int $documentId): string
    {
        return 'No se encontro el documento solicitado (#' . $documentId . ').';
    }
}

if (!function_exists('documentoViewBuildFileMeta')) {
    /**
     * @return array{
     *     exists: bool,
     *     absolutePath: ?string,
     *     publicUrl: ?string,
     *     filename: ?string,
     *     sizeBytes: ?int,
     *     sizeLabel: ?string,
     *     extension: ?string,
     *     canPreview: bool
     * }
     */
    function documentoViewBuildFileMeta(?string $ruta, string $projectRoot): array
    {
        $defaults = [
            'exists' => false,
            'absolutePath' => null,
            'publicUrl' => null,
            'filename' => null,
            'sizeBytes' => null,
            'sizeLabel' => null,
            'extension' => null,
            'canPreview' => false,
        ];

        $ruta = trim((string) $ruta);
        if ($ruta === '') {
            return $defaults;
        }

        $relative = ltrim(str_replace('\\', '/', $ruta), '/');
        $absolutePath = rtrim($projectRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        $exists = is_file($absolutePath);
        $filename = basename($relative);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $sizeBytes = null;
        $sizeLabel = null;
        if ($exists) {
            $sizeBytes = (int) @filesize($absolutePath);
            if ($sizeBytes > 0) {
                $sizeLabel = documentoViewFormatFileSize($sizeBytes);
            }
        }

        return [
            'exists' => $exists,
            'absolutePath' => $exists ? $absolutePath : null,
            'publicUrl' => '../../' . $relative,
            'filename' => $filename !== '' ? $filename : null,
            'sizeBytes' => $sizeBytes,
            'sizeLabel' => $sizeLabel,
            'extension' => $extension !== '' ? $extension : null,
            'canPreview' => $exists && in_array($extension, ['pdf'], true),
        ];
    }
}

if (!function_exists('documentoViewFormatFileSize')) {
    function documentoViewFormatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $size = (float) $bytes;
        $unit = 'KB';

        foreach ($units as $candidate) {
            $size /= 1024;
            $unit = $candidate;
            if ($size < 1024) {
                break;
            }
        }

        $precision = $size >= 10 ? 1 : 2;

        return number_format($size, $precision, '.', '') . ' ' . $unit;
    }
}

if (!function_exists('documentoViewDecorateDocument')) {
    /**
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    function documentoViewDecorateDocument(array $document): array
    {
        $document['estatus_badge_class'] = documentoRenderBadgeClass($document['estatus'] ?? null);
        $document['estatus_badge_label'] = documentoRenderBadgeLabel($document['estatus'] ?? null);
        $document['creado_en_label'] = documentoFormatDateTime($document['creado_en'] ?? null);

        $empresaId = isset($document['empresa_id']) ? (int) $document['empresa_id'] : 0;
        $empresaNombre = documentoValueOrDefault($document['empresa_nombre'] ?? null, '');
        $document['empresa_label'] = $empresaNombre !== ''
            ? $empresaNombre
            : ($empresaId > 0 ? 'Empresa #' . $empresaId : 'Empresa sin nombre');

        $document['convenio_label'] = documentoViewBuildConvenioLabel($document);

        $document['tipo_label'] = documentoValueOrDefault($document['tipo_nombre'] ?? null, 'Tipo sin nombre');

        $document['archivo_nombre'] = isset($document['ruta'])
            ? basename(str_replace('\\', '/', (string) $document['ruta']))
            : null;

        return $document;
    }
}

if (!function_exists('documentoViewBuildConvenioLabel')) {
    /**
     * @param array<string, mixed> $document
     */
    function documentoViewBuildConvenioLabel(array $document): ?string
    {
        $convenioId = documentoNormalizePositiveInt($document['convenio_id'] ?? null);
        if ($convenioId === null) {
            return null;
        }

        $parts = [];

        $folio = trim((string) ($document['convenio_folio'] ?? ''));
        if ($folio !== '') {
            $parts[] = '#' . $folio;
        } else {
            $parts[] = 'Convenio #' . $convenioId;
        }

        $version = trim((string) ($document['convenio_version'] ?? ''));
        if ($version !== '') {
            $parts[] = 'v' . $version;
        }

        $estatus = trim((string) ($document['convenio_estatus'] ?? ''));
        if ($estatus !== '') {
            $parts[] = ucfirst(strtolower($estatus));
        }

        return implode(' - ', $parts);
    }
}

if (!function_exists('documentoViewDecorateHistory')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, mixed>>
     */
    function documentoViewDecorateHistory(array $records): array
    {
        $decorated = [];

        foreach ($records as $record) {
            $decorated[] = documentoViewBuildHistoryEntry($record);
        }

        return $decorated;
    }
}

if (!function_exists('documentoViewBuildHistoryEntry')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function documentoViewBuildHistoryEntry(array $record): array
    {
        $record['estatus_badge_class'] = documentoRenderBadgeClass($record['estatus'] ?? null);
        $record['estatus_badge_label'] = documentoRenderBadgeLabel($record['estatus'] ?? null);
        $record['creado_en_label'] = documentoFormatDateTime($record['creado_en'] ?? null);
        $record['archivo_nombre'] = isset($record['ruta'])
            ? basename(str_replace('\\', '/', (string) $record['ruta']))
            : null;

        return $record;
    }
}
