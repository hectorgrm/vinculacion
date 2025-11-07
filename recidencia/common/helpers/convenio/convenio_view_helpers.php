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
     *     previewUrl: ?string,
     *     downloadLabel: string,
     *     diasRestantesLabel: string,
     *     observacionesLabel: string,
     *     tipoConvenioLabel: string,
     *     responsableAcademicoLabel: string,
     *     responsableEmpresarialLabel: string,
     *     responsableEmpresarialCargo: ?string,
     *     fechaInicioLabel: string,
     *     fechaFinLabel: string,
     *     empresaTelefonoLabel: string,
     *     empresaCorreoLabel: string,
     *     empresaDireccionLabel: string,
     *     empresaRegistroLabel: string,
     *     estatusBadgeClass: string,
     *     estatusBadgeLabel: string,
     *     parentId: ?int,
     *     parentUrl: ?string,
     *     parentEmpresaNombre: ?string,
     *     parentFechaInicioLabel: string,
     *     parentFechaFinLabel: string,
     *     parentEstatusBadgeClass: string,
     *     parentEstatusBadgeLabel: string
     * }
     */
    function convenio_prepare_view_metadata(?array $convenio): array
    {
        $metadata = [
            'empresaNombre' => null,
            'empresaUrl' => null,
            'downloadUrl' => null,
            'previewUrl' => null,
            'downloadLabel' => 'Ver PDF',
            'diasRestantesLabel' => 'N/D',
            'observacionesLabel' => 'Sin observaciones registradas.',
            'tipoConvenioLabel' => 'N/D',
            'responsableAcademicoLabel' => 'N/D',
            'responsableEmpresarialLabel' => 'N/D',
            'responsableEmpresarialCargo' => null,
            'fechaInicioLabel' => 'N/D',
            'fechaFinLabel' => 'N/D',
            'empresaTelefonoLabel' => 'N/D',
            'empresaCorreoLabel' => 'N/D',
            'empresaDireccionLabel' => 'N/D',
            'empresaRegistroLabel' => 'N/D',
            'estatusBadgeClass' => 'badge secondary',
            'estatusBadgeLabel' => 'Sin especificar',
            'parentId' => null,
            'parentUrl' => null,
            'parentEmpresaNombre' => null,
            'parentFechaInicioLabel' => 'N/D',
            'parentFechaFinLabel' => 'N/D',
            'parentEstatusBadgeClass' => 'badge secondary',
            'parentEstatusBadgeLabel' => 'Sin especificar',
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
            $metadata['previewUrl'] = $firmaUrl;
            $metadata['downloadLabel'] = 'Ver PDF firmado';
        } elseif ($borradorUrl !== '') {
            $metadata['downloadUrl'] = $borradorUrl;
            $metadata['previewUrl'] = $borradorUrl;
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

        $responsableEmpresarial = isset($convenio['empresa_representante'])
            ? trim((string) $convenio['empresa_representante'])
            : '';
        $cargoEmpresarial = isset($convenio['empresa_representante_cargo'])
            ? trim((string) $convenio['empresa_representante_cargo'])
            : '';

        if ($responsableEmpresarial !== '') {
            $metadata['responsableEmpresarialLabel'] = $responsableEmpresarial;

            if ($cargoEmpresarial !== '') {
                $metadata['responsableEmpresarialCargo'] = $cargoEmpresarial;
            }
        }

        $fechaInicio = isset($convenio['fecha_inicio_label'])
            ? trim((string) $convenio['fecha_inicio_label'])
            : '';
        if ($fechaInicio !== '') {
            $metadata['fechaInicioLabel'] = $fechaInicio;
        }

        $fechaFin = isset($convenio['fecha_fin_label'])
            ? trim((string) $convenio['fecha_fin_label'])
            : '';
        if ($fechaFin !== '') {
            $metadata['fechaFinLabel'] = $fechaFin;
        }

        $telefono = isset($convenio['empresa_telefono'])
            ? trim((string) $convenio['empresa_telefono'])
            : '';
        if ($telefono !== '') {
            $metadata['empresaTelefonoLabel'] = $telefono;
        }

        $correo = isset($convenio['empresa_contacto_email'])
            ? trim((string) $convenio['empresa_contacto_email'])
            : '';
        if ($correo !== '') {
            $metadata['empresaCorreoLabel'] = $correo;
        }

        $direccion = isset($convenio['empresa_direccion'])
            ? trim((string) $convenio['empresa_direccion'])
            : '';
        $municipio = isset($convenio['empresa_municipio'])
            ? trim((string) $convenio['empresa_municipio'])
            : '';
        $estado = isset($convenio['empresa_estado'])
            ? trim((string) $convenio['empresa_estado'])
            : '';
        $cp = isset($convenio['empresa_cp'])
            ? trim((string) $convenio['empresa_cp'])
            : '';

        $direccionPartes = [];
        if ($direccion !== '') {
            $direccionPartes[] = $direccion;
        }
        if ($municipio !== '') {
            $direccionPartes[] = $municipio;
        }
        if ($estado !== '') {
            $direccionPartes[] = $estado;
        }
        if ($cp !== '') {
            $direccionPartes[] = 'C.P. ' . $cp;
        }

        if ($direccionPartes !== []) {
            $metadata['empresaDireccionLabel'] = implode(', ', $direccionPartes);
        }

        $empresaRegistro = isset($convenio['empresa_creado_en_label'])
            ? trim((string) $convenio['empresa_creado_en_label'])
            : '';
        if ($empresaRegistro !== '') {
            $metadata['empresaRegistroLabel'] = $empresaRegistro;
        }

        $parentId = isset($convenio['parent_id']) && is_int($convenio['parent_id'])
            ? $convenio['parent_id']
            : null;

        if ($parentId !== null) {
            $metadata['parentId'] = $parentId;

            $parentUrl = isset($convenio['parent_url'])
                ? trim((string) $convenio['parent_url'])
                : '';
            if ($parentUrl !== '') {
                $metadata['parentUrl'] = $parentUrl;
            }

            $parentEmpresaNombre = isset($convenio['parent_empresa_nombre_label'])
                ? trim((string) $convenio['parent_empresa_nombre_label'])
                : '';
            if ($parentEmpresaNombre === '' && isset($convenio['empresa_nombre_label'])) {
                $parentEmpresaNombre = trim((string) $convenio['empresa_nombre_label']);
            }
            if ($parentEmpresaNombre !== '') {
                $metadata['parentEmpresaNombre'] = $parentEmpresaNombre;
            }

            $parentFechaInicio = isset($convenio['parent_fecha_inicio_label'])
                ? trim((string) $convenio['parent_fecha_inicio_label'])
                : '';
            if ($parentFechaInicio !== '') {
                $metadata['parentFechaInicioLabel'] = $parentFechaInicio;
            }

            $parentFechaFin = isset($convenio['parent_fecha_fin_label'])
                ? trim((string) $convenio['parent_fecha_fin_label'])
                : '';
            if ($parentFechaFin !== '') {
                $metadata['parentFechaFinLabel'] = $parentFechaFin;
            }

            $parentBadgeClass = isset($convenio['parent_estatus_badge_class'])
                ? trim((string) $convenio['parent_estatus_badge_class'])
                : '';
            if ($parentBadgeClass !== '') {
                $metadata['parentEstatusBadgeClass'] = $parentBadgeClass;
            }

            $parentBadgeLabel = isset($convenio['parent_estatus_badge_label'])
                ? trim((string) $convenio['parent_estatus_badge_label'])
                : '';
            if ($parentBadgeLabel !== '') {
                $metadata['parentEstatusBadgeLabel'] = $parentBadgeLabel;
            }
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

