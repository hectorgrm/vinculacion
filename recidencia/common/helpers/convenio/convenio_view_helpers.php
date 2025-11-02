<?php
declare(strict_types=1);

if (!function_exists('convenio_prepare_view_metadata')) {
    /**
     * Normaliza los datos del convenio para ser consumidos en la vista.
     *
     * @param array<string, mixed>|null $convenio
     * @return array{
     *     empresaNombre: ?string,
     *     empresaUrl: ?string,
     *     downloadUrl: ?string,
     *     downloadLabel: string,
     *     diasRestantesLabel: string,
     *     observacionesLabel: string,
     *     tipoConvenioLabel: string,
     *     responsableAcademicoLabel: string,
     *     estatusBadgeClass: string,
     *     estatusBadgeLabel: string
     * }
     */
    function convenio_prepare_view_metadata(?array $convenio): array
    {
        $metadata = [
            'empresaNombre' => null,
            'empresaUrl' => null,
            'downloadUrl' => null,
            'downloadLabel' => 'Ver PDF',
            'diasRestantesLabel' => 'N/D',
            'observacionesLabel' => 'Sin observaciones registradas.',
            'tipoConvenioLabel' => 'N/D',
            'responsableAcademicoLabel' => 'N/D',
            'estatusBadgeClass' => 'badge secondary',
            'estatusBadgeLabel' => 'Sin especificar',
        ];

        if ($convenio === null) {
            return $metadata;
        }

        $empresaNombreLabel = isset($convenio['empresa_nombre_label'])
            ? trim((string) $convenio['empresa_nombre_label'])
            : '';
        $empresaNombre = $empresaNombreLabel !== ''
            ? $empresaNombreLabel
            : (isset($convenio['empresa_nombre']) ? trim((string) $convenio['empresa_nombre']) : '');

        if ($empresaNombre !== '') {
            $metadata['empresaNombre'] = $empresaNombre;
        }

        $empresaUrl = isset($convenio['empresa_url'])
            ? trim((string) $convenio['empresa_url'])
            : '';
        if ($empresaUrl !== '') {
            $metadata['empresaUrl'] = $empresaUrl;
        }

        $firmaUrl = isset($convenio['firma_public_url'])
            ? trim((string) $convenio['firma_public_url'])
            : '';
        $borradorUrl = isset($convenio['borrador_public_url'])
            ? trim((string) $convenio['borrador_public_url'])
            : '';

        if ($firmaUrl !== '') {
            $metadata['downloadUrl'] = $firmaUrl;
            $metadata['downloadLabel'] = 'Ver PDF firmado';
        } elseif ($borradorUrl !== '') {
            $metadata['downloadUrl'] = $borradorUrl;
            $metadata['downloadLabel'] = 'Ver PDF borrador';
        }

        $diasRestantes = $convenio['dias_restantes'] ?? null;
        if (is_int($diasRestantes)) {
            if ($diasRestantes === 0) {
                $metadata['diasRestantesLabel'] = 'Vence hoy';
            } elseif ($diasRestantes > 0) {
                $metadata['diasRestantesLabel'] = $diasRestantes . ' dia' . ($diasRestantes === 1 ? '' : 's');
            } else {
                $diasRestantesAbs = abs($diasRestantes);
                $metadata['diasRestantesLabel'] = 'Vencido hace ' . $diasRestantesAbs . ' dia' . ($diasRestantesAbs === 1 ? '' : 's');
            }
        }

        $observaciones = isset($convenio['observaciones'])
            ? trim((string) $convenio['observaciones'])
            : '';
        if ($observaciones !== '') {
            $metadata['observacionesLabel'] = nl2br(htmlspecialchars($observaciones, ENT_QUOTES, 'UTF-8'));
        }

        $responsableAcademico = isset($convenio['responsable_academico'])
            ? trim((string) $convenio['responsable_academico'])
            : '';
        $tipoConvenio = isset($convenio['tipo_convenio'])
            ? trim((string) $convenio['tipo_convenio'])
            : '';

        if ($tipoConvenio !== '') {
            $metadata['tipoConvenioLabel'] = $tipoConvenio;
        }

        if ($responsableAcademico !== '') {
            $metadata['responsableAcademicoLabel'] = $responsableAcademico;
        }

        if (isset($convenio['estatus_badge_class'])) {
            $badgeClass = trim((string) $convenio['estatus_badge_class']);
            if ($badgeClass !== '') {
                $metadata['estatusBadgeClass'] = $badgeClass;
            }
        }

        if (isset($convenio['estatus_badge_label'])) {
            $badgeLabel = trim((string) $convenio['estatus_badge_label']);
            if ($badgeLabel !== '') {
                $metadata['estatusBadgeLabel'] = $badgeLabel;
            }
        }

        return $metadata;
    }
}

