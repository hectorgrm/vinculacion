<?php

declare(strict_types=1);

namespace PortalEmpresa\Helpers;

use DateTimeImmutable;

class EmpresaConvenioHelper
{
    public static function valueOrDefault(mixed $value, string $fallback = 'N/D'): string
    {
        if (is_string($value)) {
            $value = trim($value);
            return $value !== '' ? $value : $fallback;
        }

        if (is_numeric($value)) {
            $stringValue = trim((string) $value);
            return $stringValue !== '' ? $stringValue : $fallback;
        }

        return $fallback;
    }

    public static function formatDate(?string $value, string $fallback = 'N/D'): string
    {
        if ($value === null) {
            return $fallback;
        }

        $value = trim($value);

        if ($value === '') {
            return $fallback;
        }

        try {
            $date = new DateTimeImmutable($value);
            return $date->format('d/m/Y');
        } catch (\Throwable) {
            return $fallback;
        }
    }

    public static function normalizePath(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $normalized = str_replace('\\', '/', $value);

        if (preg_match('#^(?:https?:)?//#i', $normalized) === 1) {
            return $normalized;
        }

        if (preg_match('#^(?:\./|\.\./|/)#', $normalized) === 1) {
            return $normalized;
        }

        $normalized = ltrim($normalized, '/');

        return '../../recidencia/' . $normalized;
    }

    public static function ensureUrlScheme(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (preg_match('#^[a-z][a-z0-9+.-]*://#i', $value) === 1) {
            return $value;
        }

        if (str_starts_with($value, '//')) {
            return $value;
        }

        return 'https://' . ltrim($value, '/');
    }

    public static function mapBadgeVariant(?string $estatus): string
    {
        $estatus = is_string($estatus) ? trim($estatus) : '';

        return match ($estatus) {
            'Activa' => 'ok',
            'En revisión' => 'warn',
            'Suspendida', 'Inactiva' => 'danger',
            default => 'warn',
        };
    }

    /**
     * @return array{badge_variant: string, badge_label: string, strip_variant: string, strip_title: string, strip_message: string, has_convenio: bool}
     */
    public static function statusMeta(?string $estatus, bool $hasConvenio = true): array
    {
        $estatus = is_string($estatus) ? trim($estatus) : '';

        if (!$hasConvenio || $estatus === '') {
            return [
                'badge_variant' => 'warn',
                'badge_label' => 'Sin convenio',
                'strip_variant' => 'warnbox',
                'strip_title' => '🕒 Convenio pendiente.',
                'strip_message' => 'Aún no se ha registrado un convenio para tu empresa.',
                'has_convenio' => false,
            ];
        }

        return match ($estatus) {
            'Activa' => [
                'badge_variant' => 'ok',
                'badge_label' => 'Activa',
                'strip_variant' => 'okbox',
                'strip_title' => '✅ Convenio activo.',
                'strip_message' => 'Tu convenio se encuentra vigente y disponible.',
                'has_convenio' => true,
            ],
            'En revisión' => [
                'badge_variant' => 'warn',
                'badge_label' => 'En revisión',
                'strip_variant' => 'warnbox',
                'strip_title' => '🕒 Convenio en revisión.',
                'strip_message' => 'El convenio está en proceso de revisión por Vinculación.',
                'has_convenio' => true,
            ],
            'Suspendida' => [
                'badge_variant' => 'danger',
                'badge_label' => 'Suspendida',
                'strip_variant' => 'dangerbox',
                'strip_title' => '⛔ Convenio suspendido.',
                'strip_message' => 'Contacta al área de Vinculación para regularizar tu convenio.',
                'has_convenio' => true,
            ],
            'Inactiva' => [
                'badge_variant' => 'danger',
                'badge_label' => 'Inactiva',
                'strip_variant' => 'dangerbox',
                'strip_title' => '⛔ Convenio inactivo.',
                'strip_message' => 'No hay un convenio vigente actualmente.',
                'has_convenio' => true,
            ],
            default => [
                'badge_variant' => 'warn',
                'badge_label' => $estatus,
                'strip_variant' => 'warnbox',
                'strip_title' => 'ℹ️ Estatus del convenio.',
                'strip_message' => 'Revisa con Vinculación para conocer más detalles.',
                'has_convenio' => true,
            ],
        };
    }

    /**
     * @param array<string, mixed>|null $convenio
     * @return array<string, mixed>|null
     */
    public static function decorateConvenio(?array $convenio): ?array
    {
        if ($convenio === null) {
            return null;
        }

        $estatus = isset($convenio['estatus']) ? trim((string) $convenio['estatus']) : '';
        $folio = self::valueOrDefault($convenio['folio'] ?? null, 'Sin folio');
        $tipo = self::valueOrDefault($convenio['tipo_convenio'] ?? null, 'No especificado');
        $responsable = self::valueOrDefault($convenio['responsable_academico'] ?? null, 'Sin asignar');
        $fechaInicio = isset($convenio['fecha_inicio']) ? (string) $convenio['fecha_inicio'] : null;
        $fechaFin = isset($convenio['fecha_fin']) ? (string) $convenio['fecha_fin'] : null;
        $observaciones = isset($convenio['observaciones']) ? trim((string) $convenio['observaciones']) : '';

        $borradorUrl = self::normalizePath($convenio['borrador_path'] ?? null);
        $firmadoUrl = self::normalizePath($convenio['firmado_path'] ?? null);

        $primaryUrl = $firmadoUrl ?? $borradorUrl;
        $embedUrl = $primaryUrl;

        if ($embedUrl !== null && strpos($embedUrl, '#') === false) {
            $embedUrl .= '#view=FitH';
        }

        $documentSource = null;

        if ($firmadoUrl !== null) {
            $documentSource = 'firmado';
        } elseif ($borradorUrl !== null) {
            $documentSource = 'borrador';
        }

        $documentLabel = match ($documentSource) {
            'firmado' => 'Documento firmado',
            'borrador' => 'Documento en borrador',
            default => 'Sin documento',
        };

        return array_merge($convenio, [
            'estatus' => $estatus !== '' ? $estatus : null,
            'folio_label' => $folio,
            'tipo_label' => $tipo,
            'responsable_label' => $responsable,
            'fecha_inicio_label' => self::formatDate($fechaInicio),
            'fecha_fin_label' => self::formatDate($fechaFin),
            'observaciones_plain' => $observaciones,
            'observaciones_label' => $observaciones !== '' ? $observaciones : 'Sin observaciones registradas.',
            'borrador_url' => $borradorUrl,
            'firmado_url' => $firmadoUrl,
            'document_url' => $primaryUrl,
            'document_embed_url' => $embedUrl,
            'document_source' => $documentSource,
            'document_label' => $documentLabel,
            'has_document' => $primaryUrl !== null,
        ]);
    }

    /**
     * @param array<string, mixed>|null $empresa
     * @return array<string, mixed>|null
     */
    public static function decorateEmpresa(?array $empresa): ?array
    {
        if ($empresa === null) {
            return null;
        }

        $nombre = isset($empresa['nombre']) ? trim((string) $empresa['nombre']) : '';
        $numeroControl = isset($empresa['numero_control']) ? trim((string) $empresa['numero_control']) : '';
        $rfc = isset($empresa['rfc']) ? trim((string) $empresa['rfc']) : '';
        $sector = isset($empresa['sector']) ? trim((string) $empresa['sector']) : '';
        $regimenFiscal = isset($empresa['regimen_fiscal']) ? trim((string) $empresa['regimen_fiscal']) : '';
        $representante = isset($empresa['representante']) ? trim((string) $empresa['representante']) : '';
        $cargoRepresentante = isset($empresa['cargo_representante']) ? trim((string) $empresa['cargo_representante']) : '';
        $contactoNombre = isset($empresa['contacto_nombre']) ? trim((string) $empresa['contacto_nombre']) : '';
        $contactoEmail = isset($empresa['contacto_email']) ? trim((string) $empresa['contacto_email']) : '';
        $telefono = isset($empresa['telefono']) ? trim((string) $empresa['telefono']) : '';
        $sitioWebRaw = isset($empresa['sitio_web']) ? trim((string) $empresa['sitio_web']) : '';
        $estatus = isset($empresa['estatus']) ? trim((string) $empresa['estatus']) : '';

        $decorated = $empresa;

        $decorated['nombre'] = $nombre !== '' ? $nombre : 'Empresa';
        $decorated['numero_control'] = $numeroControl;
        $decorated['numero_control_label'] = $numeroControl !== '' ? $numeroControl : 'No asignado';
        $decorated['rfc_label'] = $rfc !== '' ? $rfc : 'No registrado';
        $decorated['sector_label'] = $sector !== '' ? $sector : 'No especificado';
        $decorated['regimen_fiscal_label'] = $regimenFiscal !== '' ? $regimenFiscal : 'No registrado';
        $decorated['representante'] = $representante;
        $decorated['representante_label'] = $representante !== '' ? $representante : 'No registrado';
        $decorated['cargo_representante'] = $cargoRepresentante;
        $decorated['cargo_representante_label'] = $cargoRepresentante !== '' ? $cargoRepresentante : 'No registrado';
        $decorated['contacto_nombre'] = $contactoNombre;
        $decorated['contacto_nombre_label'] = $contactoNombre !== '' ? $contactoNombre : 'No registrado';
        $decorated['contacto_email'] = $contactoEmail;
        $decorated['contacto_email_label'] = $contactoEmail !== '' ? $contactoEmail : 'No registrado';
        $decorated['telefono'] = $telefono;
        $decorated['telefono_label'] = $telefono !== '' ? $telefono : 'No registrado';
        $decorated['sitio_web'] = $sitioWebRaw;
        $decorated['sitio_web_label'] = $sitioWebRaw !== '' ? $sitioWebRaw : 'No registrado';
        $decorated['sitio_web_url'] = $sitioWebRaw !== '' ? self::ensureUrlScheme($sitioWebRaw) : null;
        $decorated['direccion_label'] = self::buildAddress($empresa);
        $decorated['estatus'] = $estatus !== '' ? $estatus : null;
        $decorated['estatus_badge_variant'] = self::mapBadgeVariant($decorated['estatus']);
        $decorated['estatus_label'] = $decorated['estatus'] ?? 'Sin estatus';

        return $decorated;
    }

    /**
     * @param array<string, mixed> $empresa
     */
    private static function buildAddress(array $empresa): string
    {
        $parts = [];

        foreach (['direccion', 'municipio', 'estado'] as $field) {
            if (!isset($empresa[$field])) {
                continue;
            }

            $value = trim((string) $empresa[$field]);

            if ($value === '') {
                continue;
            }

            $parts[] = $value;
        }

        if (isset($empresa['cp'])) {
            $cp = trim((string) $empresa['cp']);

            if ($cp !== '') {
                $parts[] = 'C.P. ' . $cp;
            }
        }

        return $parts !== [] ? implode(', ', $parts) : 'Sin dirección registrada';
    }
}
