<?php

declare(strict_types=1);

if (!function_exists('convenioRenewalAllowedStatuses')) {
    /**
     * @return array<int, string>
     */
    function convenioRenewalAllowedStatuses(): array
    {
        return ['Activa', 'Completado'];
    }
}

if (!function_exists('convenioRenewalIsStatusAllowed')) {
    function convenioRenewalIsStatusAllowed(?string $estatus): bool
    {
        if ($estatus === null) {
            return false;
        }

        $value = trim($estatus);

        if ($value === '') {
            return false;
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($value, 'UTF-8')
            : strtolower($value);

        foreach (convenioRenewalAllowedStatuses() as $allowed) {
            $allowedNormalized = function_exists('mb_strtolower')
                ? mb_strtolower($allowed, 'UTF-8')
                : strtolower($allowed);

            if ($normalized === $allowedNormalized) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('convenioRenewalFormDefaults')) {
    /**
     * @param array<string, mixed>|null $original
     * @return array<string, string>
     */
    function convenioRenewalFormDefaults(?array $original = null): array
    {
        $defaults = [
            'fecha_inicio' => '',
            'fecha_fin' => '',
            'observaciones' => '',
        ];

        if ($original !== null && isset($original['observaciones']) && $original['observaciones'] !== null) {
            $defaults['observaciones'] = trim((string) $original['observaciones']);
        }

        return $defaults;
    }
}

if (!function_exists('convenioRenewalSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    function convenioRenewalSanitizeInput(array $input, ?array $original = null): array
    {
        $data = convenioRenewalFormDefaults($original);

        foreach ($data as $key => $_) {
            if (!array_key_exists($key, $input)) {
                continue;
            }

            $value = $input[$key];

            if (is_scalar($value)) {
                $data[$key] = trim((string) $value);
            }
        }

        return $data;
    }
}

if (!function_exists('convenioRenewalValidate')) {
    /**
     * @param array<string, string> $formData
     * @return array<int, string>
     */
    function convenioRenewalValidate(array $formData): array
    {
        $errors = [];

        $fechaInicio = $formData['fecha_inicio'] ?? '';
        $fechaFin = $formData['fecha_fin'] ?? '';

        if ($fechaInicio === '') {
            $errors[] = 'Debes indicar la nueva fecha de inicio del convenio.';
        }

        if ($fechaFin === '') {
            $errors[] = 'Debes indicar la nueva fecha de término del convenio.';
        }

        $inicioValida = null;
        $finValida = null;

        if ($fechaInicio !== '') {
            $inicioValida = DateTime::createFromFormat('Y-m-d', $fechaInicio);

            if ($inicioValida === false || $inicioValida->format('Y-m-d') !== $fechaInicio) {
                $errors[] = 'La fecha de inicio no tiene un formato válido (AAAA-MM-DD).';
                $inicioValida = null;
            }
        }

        if ($fechaFin !== '') {
            $finValida = DateTime::createFromFormat('Y-m-d', $fechaFin);

            if ($finValida === false || $finValida->format('Y-m-d') !== $fechaFin) {
                $errors[] = 'La fecha de término no tiene un formato válido (AAAA-MM-DD).';
                $finValida = null;
            }
        }

        if ($inicioValida !== null && $finValida !== null && $inicioValida > $finValida) {
            $errors[] = 'La fecha de término debe ser posterior o igual a la fecha de inicio.';
        }

        return $errors;
    }
}

if (!function_exists('convenioRenewalFormValue')) {
    /**
     * @param array<string, string> $formData
     */
    function convenioRenewalFormValue(array $formData, string $field): string
    {
        return isset($formData[$field]) ? (string) $formData[$field] : '';
    }
}

if (!function_exists('convenioRenewalBuildPayload')) {
    /**
     * @param array<string, string> $formData
     * @param array<string, mixed> $original
     * @return array<string, mixed>
     */
    function convenioRenewalBuildPayload(array $formData, array $original): array
    {
        return [
            'empresa_id' => isset($original['empresa_id']) ? (int) $original['empresa_id'] : 0,
            'renovado_de' => isset($original['id']) ? (int) $original['id'] : null,
            'tipo_convenio' => $original['tipo_convenio'] ?? null,
            'responsable_academico' => $original['responsable_academico'] ?? null,
            'observaciones' => $formData['observaciones'] !== '' ? $formData['observaciones'] : null,
            'fecha_inicio' => $formData['fecha_inicio'] !== '' ? $formData['fecha_inicio'] : null,
            'fecha_fin' => $formData['fecha_fin'] !== '' ? $formData['fecha_fin'] : null,
            'estatus' => 'En revisión',
            'folio' => null,
            'borrador_path' => null,
            'firmado_path' => null,
        ];
    }
}

if (!function_exists('convenioRenewalStatusBadgeClass')) {
    function convenioRenewalStatusBadgeClass(string $estatus): string
    {
        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        return match ($normalized) {
            'activa' => 'badge ok',
            'completado' => 'badge secondary',
            'en revisión', 'en revision' => 'badge secondary',
            'inactiva' => 'badge warn',
            'suspendida' => 'badge err',
            default => 'badge secondary',
        };
    }
}

if (!function_exists('convenioRenewalFormatDate')) {
    function convenioRenewalFormatDate(?string $value): string
    {
        if ($value === null) {
            return 'N/D';
        }

        $trimmed = trim($value);

        if ($trimmed === '') {
            return 'N/D';
        }

        $date = DateTime::createFromFormat('Y-m-d', $trimmed);

        if ($date === false) {
            return $trimmed;
        }

        return $date->format('d/m/Y');
    }
}

if (!function_exists('convenioRenewalFormatDateRange')) {
    function convenioRenewalFormatDateRange(?string $inicio, ?string $fin): string
    {
        $inicioLabel = convenioRenewalFormatDate($inicio);
        $finLabel = convenioRenewalFormatDate($fin);

        if ($inicioLabel === 'N/D' && $finLabel === 'N/D') {
            return 'Sin vigencia registrada';
        }

        return $inicioLabel . ' — ' . $finLabel;
    }
}

if (!function_exists('convenioRenewalPrepareOriginalMetadata')) {
    /**
     * @param array<string, mixed>|null $original
     * @return array<string, string>
     */
    function convenioRenewalPrepareOriginalMetadata(?array $original): array
    {
        if ($original === null) {
            return [
                'empresaNombre' => 'Empresa no disponible',
                'empresaNumeroControl' => '',
                'tipoConvenio' => 'N/D',
                'responsableAcademico' => 'N/D',
                'estatusLabel' => 'N/D',
                'estatusBadgeClass' => 'badge secondary',
                'vigenciaLabel' => 'Sin vigencia registrada',
                'fechaInicioLabel' => 'N/D',
                'fechaFinLabel' => 'N/D',
                'observacionesLabel' => 'Sin observaciones registradas.',
                'folioLabel' => 'Sin folio asignado',
                'convenioIdLabel' => 'N/D',
            ];
        }

        $empresaNombre = isset($original['empresa_nombre'])
            ? trim((string) $original['empresa_nombre'])
            : '';

        if ($empresaNombre === '') {
            $empresaNombre = 'Empresa sin nombre';
        }

        $numeroControl = isset($original['empresa_numero_control'])
            ? trim((string) $original['empresa_numero_control'])
            : '';

        $tipoConvenio = isset($original['tipo_convenio'])
            ? trim((string) $original['tipo_convenio'])
            : '';

        $responsable = isset($original['responsable_academico'])
            ? trim((string) $original['responsable_academico'])
            : '';

        $estatus = isset($original['estatus'])
            ? trim((string) $original['estatus'])
            : 'N/D';

        $observaciones = isset($original['observaciones']) && $original['observaciones'] !== null
            ? trim((string) $original['observaciones'])
            : '';

        $folio = isset($original['folio']) && $original['folio'] !== null
            ? trim((string) $original['folio'])
            : '';

        $convenioId = isset($original['id']) ? (string) $original['id'] : 'N/D';

        return [
            'empresaNombre' => $empresaNombre,
            'empresaNumeroControl' => $numeroControl,
            'tipoConvenio' => $tipoConvenio !== '' ? $tipoConvenio : 'N/D',
            'responsableAcademico' => $responsable !== '' ? $responsable : 'N/D',
            'estatusLabel' => $estatus !== '' ? $estatus : 'N/D',
            'estatusBadgeClass' => convenioRenewalStatusBadgeClass($estatus !== '' ? $estatus : 'N/D'),
            'vigenciaLabel' => convenioRenewalFormatDateRange(
                isset($original['fecha_inicio']) ? (string) $original['fecha_inicio'] : null,
                isset($original['fecha_fin']) ? (string) $original['fecha_fin'] : null
            ),
            'fechaInicioLabel' => convenioRenewalFormatDate(
                isset($original['fecha_inicio']) ? (string) $original['fecha_inicio'] : null
            ),
            'fechaFinLabel' => convenioRenewalFormatDate(
                isset($original['fecha_fin']) ? (string) $original['fecha_fin'] : null
            ),
            'observacionesLabel' => $observaciones !== '' ? $observaciones : 'Sin observaciones registradas.',
            'folioLabel' => $folio !== '' ? $folio : 'Sin folio asignado',
            'convenioIdLabel' => $convenioId,
        ];
    }
}
