<?php

declare(strict_types=1);

if (!function_exists('documentoTipoListDefaults')) {
    /**
     * @return array{
     *     q: string,
     *     tipo_empresa: string,
     *     tipos: array<int, array<string, mixed>>,
     *     tipoEmpresaOptions: array<string, string>,
     *     errorMessage: ?string,
     *     statusMessage: ?string
     * }
     */
    function documentoTipoListDefaults(): array
    {
        return [
            'q' => '',
            'tipo_empresa' => '',
            'tipos' => [],
            'tipoEmpresaOptions' => documentoTipoEmpresaOptions(),
            'errorMessage' => null,
            'statusMessage' => null,
        ];
    }
}

if (!function_exists('documentoTipoNormalizeSearch')) {
    function documentoTipoNormalizeSearch(?string $value): string
    {
        return trim((string) $value);
    }
}

if (!function_exists('documentoTipoNormalizeTipoEmpresa')) {
    function documentoTipoNormalizeTipoEmpresa(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $typedValue = strtolower(trim((string) $value));
        if ($typedValue === '') {
            return null;
        }

        foreach (documentoTipoEmpresaOptions() as $optionValue => $_label) {
            if ($typedValue === $optionValue) {
                return $optionValue;
            }
        }

        return null;
    }
}

if (!function_exists('documentoTipoEmpresaOptions')) {
    /**
     * @return array<string, string>
     */
    function documentoTipoEmpresaOptions(): array
    {
        return [
            'fisica' => 'Empresa Fiscal',
            'moral' => 'Empresa Moral',
            'ambas' => 'Ambas',
        ];
    }
}

if (!function_exists('documentoTipoRenderEmpresaLabel')) {
    function documentoTipoRenderEmpresaLabel(?string $tipoEmpresa): string
    {
        $tipoEmpresa = strtolower(trim((string) $tipoEmpresa));
        if ($tipoEmpresa === '') {
            return 'Sin especificar';
        }

        return documentoTipoEmpresaOptions()[$tipoEmpresa] ?? ucfirst($tipoEmpresa);
    }
}

if (!function_exists('documentoTipoRenderObligatorioLabel')) {
    function documentoTipoRenderObligatorioLabel(mixed $value): string
    {
        $boolValue = documentoTipoCastBool($value);

        return $boolValue ? 'Si' : 'No';
    }
}

if (!function_exists('documentoTipoRenderObligatorioClass')) {
    function documentoTipoRenderObligatorioClass(mixed $value): string
    {
        $boolValue = documentoTipoCastBool($value);

        return $boolValue ? 'badge ok' : 'badge warn';
    }
}

if (!function_exists('documentoTipoCastBool')) {
    function documentoTipoCastBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return false;
        }

        if (is_int($value)) {
            return $value === 1;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            return in_array($normalized, ['1', 'true', 'si', 'yes', 'on'], true);
        }

        return false;
    }
}

if (!function_exists('documentoTipoValueOrDefault')) {
    function documentoTipoValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $value = trim((string) $value);

            return $value !== '' ? $value : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('documentoTipoListStatusMessage')) {
    function documentoTipoListStatusMessage(?string $statusCode, ?string $nombre): ?string
    {
        if ($statusCode === null || $statusCode === '') {
            return null;
        }

        $nombreLabel = null;
        if ($nombre !== null) {
            $trimmed = trim($nombre);
            if ($trimmed !== '') {
                $nombreLabel = $trimmed;
            }
        }

        switch ($statusCode) {
            case 'deleted':
                return $nombreLabel !== null
                    ? 'El tipo de documento "' . $nombreLabel . '" se elimino correctamente.'
                    : 'El tipo de documento se elimino correctamente.';
            case 'deactivated':
                return $nombreLabel !== null
                    ? 'El tipo de documento "' . $nombreLabel . '" se desactivo y ya no aparecera en nuevos registros.'
                    : 'El tipo de documento se desactivo y ya no aparecera en nuevos registros.';
            default:
                return null;
        }
    }
}
