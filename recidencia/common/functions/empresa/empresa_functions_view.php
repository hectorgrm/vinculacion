<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';

if (!function_exists('empresaViewDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     conveniosActivos: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    function empresaViewDefaults(): array
    {
        return [
            'empresaId' => null,
            'empresa' => null,
            'conveniosActivos' => [],
            'controllerError' => null,
            'notFoundMessage' => null,
            'inputError' => null,
        ];
    }
}

if (!function_exists('empresaViewNormalizeId')) {
    function empresaViewNormalizeId(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_string($value) && preg_match('/^\d+$/', $value) === 1) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        return null;
    }
}

if (!function_exists('empresaViewControllerErrorMessage')) {
    function empresaViewControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== '' ? $message : 'No se pudo obtener la informacion de la empresa.';
    }
}

if (!function_exists('empresaViewNotFoundMessage')) {
    function empresaViewNotFoundMessage(int $empresaId): string
    {
        return 'No se encontro la empresa solicitada (#' . $empresaId . ').';
    }
}

if (!function_exists('empresaViewInputErrorMessage')) {
    function empresaViewInputErrorMessage(): string
    {
        return 'No se proporciono un identificador de empresa valido.';
    }
}

if (!function_exists('empresaViewFormatDate')) {
    function empresaViewFormatDate(?string $value, string $fallback = 'N/A'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return $fallback;
        }

        try {
            $date = new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y');
    }
}

if (!function_exists('empresaViewValueOrDefault')) {
    function empresaViewValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $stringValue = trim((string) $value);

            return $stringValue !== '' ? $stringValue : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('empresaViewDecorate')) {
    /**
     * @param array<string, mixed> $empresa
     * @return array<string, mixed>
     */
    function empresaViewDecorate(array $empresa): array
    {
        $empresa['estatus_badge_class'] = renderBadgeClass($empresa['estatus'] ?? null);
        $empresa['estatus_badge_label'] = renderBadgeLabel($empresa['estatus'] ?? null);
        $empresa['creado_en_label'] = empresaViewFormatDate($empresa['creado_en'] ?? null);
        $empresa['actualizado_en_label'] = empresaViewFormatDate($empresa['actualizado_en'] ?? null, 'Sin actualizar');

        $empresa['nombre_label'] = empresaViewValueOrDefault($empresa['nombre'] ?? null, 'Sin nombre');
        $empresa['rfc_label'] = empresaViewValueOrDefault($empresa['rfc'] ?? null);
        $empresa['representante_label'] = empresaViewValueOrDefault($empresa['representante'] ?? null, 'No especificado');
        $empresa['telefono_label'] = empresaViewValueOrDefault($empresa['telefono'] ?? null, 'No registrado');
        $empresa['correo_label'] = empresaViewValueOrDefault($empresa['contacto_email'] ?? null, 'No registrado');

        return $empresa;
    }
}

if (!function_exists('empresaViewDecorateConvenio')) {
    /**
     * @param array<string, mixed> $convenio
     * @return array<string, mixed>
     */
    function empresaViewDecorateConvenio(array $convenio): array
    {
        $convenio['id_label'] = isset($convenio['id']) ? '#' . (string) $convenio['id'] : '#';
        $convenio['version_label'] = empresaViewValueOrDefault(
            $convenio['version_actual'] ?? $convenio['machote_version'] ?? null,
            'Sin version'
        );
        $convenio['fecha_inicio_label'] = empresaViewFormatDate($convenio['fecha_inicio'] ?? null);
        $convenio['fecha_fin_label'] = empresaViewFormatDate($convenio['fecha_fin'] ?? null);
        $convenio['estatus_badge_class'] = renderBadgeClass($convenio['estatus'] ?? null);
        $convenio['estatus_badge_label'] = renderBadgeLabel($convenio['estatus'] ?? null);

        if (isset($convenio['id'])) {
            $id = (int) $convenio['id'];
            $convenio['view_url'] = '../convenio/convenio_view.php?id=' . urlencode((string) $id);
            $convenio['edit_url'] = '../convenio/convenio_edit.php?id=' . urlencode((string) $id);
        }

        return $convenio;
    }
}

if (!function_exists('empresaViewDecorateConvenios')) {
    /**
     * @param array<int, array<string, mixed>> $convenios
     * @return array<int, array<string, mixed>>
     */
    function empresaViewDecorateConvenios(array $convenios): array
    {
        $decorated = [];

        foreach ($convenios as $convenio) {
            if (!is_array($convenio)) {
                continue;
            }

            $decorated[] = empresaViewDecorateConvenio($convenio);
        }

        return $decorated;
    }
}
