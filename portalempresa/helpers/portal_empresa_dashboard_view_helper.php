<?php
declare(strict_types=1);

namespace PortalEmpresa\Helpers;

use DateTimeImmutable;
use PortalEmpresa\Helpers\EmpresaConvenioHelper;

final class PortalEmpresaDashboardViewHelper
{
    private const DEFAULT_STATS = [
        'comentarios_total' => 0,
        'comentarios_pendientes' => 0,
        'comentarios_resueltos' => 0,
        'avance' => 0,
        'estado_revision' => 'Sin documento',
    ];

    /**
     * @param array<string, mixed> $portalSession
     * @param array<string, mixed> $dashboard
     * @return array<string, mixed>
     */
    public static function createViewModel(array $portalSession, array $dashboard, ?string $dashboardError): array
    {
        $empresa = is_array($dashboard['empresa'] ?? null) ? $dashboard['empresa'] : [];
        $convenio = is_array($dashboard['convenio'] ?? null) ? $dashboard['convenio'] : [];
        $machoteResumen = is_array($dashboard['machote'] ?? null) ? $dashboard['machote'] : [];
        $machoteStats = self::normalizeStats($dashboard['stats'] ?? []);

        return [
            'empresaNombre' => self::resolveEmpresaNombre($empresa, $portalSession),
            'empresaLogoUrl' => self::resolveEmpresaLogoUrl($empresa),
            'ultimoAccesoLabel' => self::formatLastAccess($portalSession['autenticado_en'] ?? ''),
            'dashboardError' => self::normalizeError($dashboardError),
            'convenio' => self::buildConvenioSection($convenio),
            'machote' => self::buildMachoteSection($machoteResumen, $machoteStats),
            'stats' => self::buildStatsSection($machoteStats),
        ];
    }

    /**
     * @param array<string, mixed> $empresa
     * @param array<string, mixed> $portalSession
     */
    private static function resolveEmpresaNombre(array $empresa, array $portalSession): string
    {
        $nombre = '';

        if (isset($empresa['nombre'])) {
            $nombre = trim((string) $empresa['nombre']);
        }

        if ($nombre === '') {
            $nombre = trim((string) ($portalSession['empresa_nombre'] ?? ''));
        }

        return $nombre !== '' ? $nombre : 'Empresa';
    }

    private static function formatLastAccess(?string $rawValue): string
    {
        return self::formatDateValue($rawValue);
    }

    /**
     * @param mixed $stats
     * @return array<string, mixed>
     */
    private static function normalizeStats($stats): array
    {
        if (!is_array($stats)) {
            return self::DEFAULT_STATS;
        }

        return array_merge(self::DEFAULT_STATS, $stats);
    }

    private static function normalizeError(?string $error): ?string
    {
        if ($error === null) {
            return null;
        }

        $trimmed = trim($error);
        return $trimmed === '' ? null : $trimmed;
    }

    /**
     * @param array<string, mixed> $convenio
     * @return array<string, string>
     */
    private static function buildConvenioSection(array $convenio): array
    {
        $folio = EmpresaConvenioHelper::valueOrDefault($convenio['folio'] ?? null, 'Sin folio');
        $inicio = EmpresaConvenioHelper::formatDate($convenio['fecha_inicio'] ?? null, 'N/D');
        $fin = EmpresaConvenioHelper::formatDate($convenio['fecha_fin'] ?? null, 'N/D');
        $estatus = trim((string) ($convenio['estatus'] ?? ''));
        $badgeVariant = $estatus !== ''
            ? EmpresaConvenioHelper::mapConvenioBadgeVariant($estatus)
            : 'warn';

        if ($estatus === '') {
            $estatus = 'Sin convenio';
        }

        return [
            'folio' => $folio,
            'vigenciaInicio' => $inicio,
            'vigenciaFin' => $fin,
            'estatus' => $estatus,
            'badgeVariant' => $badgeVariant,
        ];
    }

    /**
     * @param array<string, mixed> $machoteResumen
     * @param array<string, mixed> $machoteStats
     * @return array<string, mixed>
     */
    private static function buildMachoteSection(array $machoteResumen, array $machoteStats): array
    {
        $machoteId = (int) ($machoteResumen['id'] ?? 0);
        $estado = trim((string) ($machoteStats['estado_revision'] ?? 'Sin documento'));
        $estado = $estado !== '' ? $estado : 'Sin documento';
        $revisionVariant = self::mapRevisionVariant($estado);

        $version = trim((string) ($machoteResumen['version'] ?? ''));
        if ($version === '') {
            $version = 'v1.0';
        }

        $documentoPdfUrl = isset($machoteResumen['documento_pdf_url']) && $machoteResumen['documento_pdf_url'] !== null
            ? (string) $machoteResumen['documento_pdf_url']
            : null;

        $documentoFuente = isset($machoteResumen['documento_fuente']) && $machoteResumen['documento_fuente'] !== null
            ? (string) $machoteResumen['documento_fuente']
            : null;

        $documentoFuenteLabel = self::formatDocumentSourceLabel($documentoFuente);

        return [
            'id' => $machoteId,
            'estado' => $estado,
            'revisionVariant' => $revisionVariant,
            'version' => $version,
            'actualizadoLabel' => self::formatDateValue($machoteResumen['actualizado_en'] ?? ''),
            'documentoFuenteLabel' => $documentoFuenteLabel,
            'documentoPdfUrl' => $documentoPdfUrl,
            'revisionLink' => $machoteId > 0 ? 'machote_view.php?id=' . urlencode((string) $machoteId) : null,
            'aprobadoLink' => $machoteId > 0 ? 'machote_view_aprobado.php?id=' . urlencode((string) $machoteId) : null,
            'confirmado' => !empty($machoteResumen['confirmado']),
        ];
    }

    /**
     * @param array<string, mixed> $machoteStats
     */
    private static function buildStatsSection(array $machoteStats): array
    {
        $pendientes = max(0, (int) ($machoteStats['comentarios_pendientes'] ?? 0));
        $resueltos = max(0, (int) ($machoteStats['comentarios_resueltos'] ?? 0));
        $avance = max(0, min(100, (int) ($machoteStats['avance'] ?? 0)));

        return [
            'comentariosPendientes' => $pendientes,
            'comentariosResueltos' => $resueltos,
            'avancePct' => $avance,
        ];
    }

    private static function formatDateValue(?string $rawValue): string
    {
        $rawValue = trim((string) ($rawValue ?? ''));

        if ($rawValue === '') {
            return '';
        }

        try {
            return (new DateTimeImmutable($rawValue))->format('d/m/Y H:i');
        } catch (\Throwable) {
            return $rawValue;
        }
    }

    private static function formatDocumentSourceLabel(?string $documentoFuente): ?string
    {
        if ($documentoFuente === null) {
            return null;
        }

        $normalized = function_exists('mb_strtolower') ? mb_strtolower($documentoFuente, 'UTF-8') : strtolower($documentoFuente);
        $normalized = trim($normalized);
        return $normalized === 'firmado' ? 'firmado' : 'borrador';
    }

    private static function resolveEmpresaLogoUrl(array $empresa): string
    {
        $logoUrl = null;

        if (isset($empresa['logo_path'])) {
            $logoUrl = self::buildEmpresaLogoUrl($empresa['logo_path']);
        }

        return $logoUrl ?? 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
    }

    private static function buildEmpresaLogoUrl(?string $logoPath): ?string
    {
        $normalized = self::normalizeLogoPath($logoPath);

        if ($normalized === null) {
            return null;
        }

        if (preg_match('/^https?:\\/\\//i', $normalized) === 1) {
            return $normalized;
        }

        return '../../recidencia/' . $normalized;
    }

    private static function normalizeLogoPath(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        if ($trimmed === '') {
            return null;
        }

        $normalized = str_replace('\\', '/', $trimmed);
        $normalized = ltrim($normalized, '/');

        if ($normalized === '' || str_contains($normalized, '..')) {
            return null;
        }

        return $normalized;
    }

    private static function mapRevisionVariant(string $estado): string
    {
        $normalized = function_exists('mb_strtolower') ? mb_strtolower($estado, 'UTF-8') : strtolower($estado);

        $approved = ['aprobado'];
        $warn = [
            'en revisión',
            'en revision',
            'en revisián',
            'en revisiÃ³n',
            'en revisiA3n',
            'pendiente de confirmación',
            'pendiente de confirmacion',
            'pendiente de confirmaciÃ³n',
            'pendiente de confirmaciA3n',
        ];

        if (in_array($normalized, $approved, true)) {
            return 'ok';
        }

        if (in_array($normalized, $warn, true)) {
            return 'warn';
        }

        return 'warn';
    }
}
