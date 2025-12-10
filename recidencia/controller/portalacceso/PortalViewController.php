<?php

declare(strict_types=1);

namespace Residencia\Controller\PortalAcceso;

require_once __DIR__ . '/../../model/portalacceso/PortalViewModel.php';
require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_list_functions.php';

use Residencia\Model\PortalAcceso\PortalViewModel;
use RuntimeException;
use PDOException;
use function portalAccessResolveStatus;
use function portalAccessStatusBadgeClass;
use function portalAccessStatusLabel;

class PortalViewController
{
    private PortalViewModel $model;

    public function __construct(?PortalViewModel $model = null)
    {
        $this->model = $model ?? new PortalViewModel();
    }

    /**
     * @return array{
     *     portal: ?array<string, mixed>,
     *     empresa: ?array<string, mixed>
     * }
     */
    public function obtenerDetalle(int $portalId): array
    {
        try {
            $record = $this->model->findById($portalId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el acceso solicitado.', 0, $exception);
        }

        if ($record === null) {
            return [
                'portal' => null,
                'empresa' => null,
            ];
        }

        $status = portalAccessResolveStatus($record['activo'] ?? null, $record['expiracion'] ?? null);
        $empresaNombre = isset($record['empresa_nombre']) ? (string) $record['empresa_nombre'] : '';
        $empresaNumeroControl = isset($record['empresa_numero_control']) && $record['empresa_numero_control'] !== null
            ? (string) $record['empresa_numero_control']
            : '';
        $empresaLabel = trim($empresaNombre);

        if ($empresaNumeroControl !== '') {
            $empresaLabel .= $empresaLabel !== '' ? ' - ' . $empresaNumeroControl : $empresaNumeroControl;
        }

        $nip = isset($record['nip']) && $record['nip'] !== null ? (string) $record['nip'] : '';
        $expiracion = isset($record['expiracion']) && $record['expiracion'] !== null ? (string) $record['expiracion'] : null;
        $creadoEn = isset($record['creado_en']) && $record['creado_en'] !== null ? (string) $record['creado_en'] : null;

        $portal = [
            'id' => isset($record['id']) ? (int) $record['id'] : null,
            'empresa_id' => isset($record['empresa_id']) ? (int) $record['empresa_id'] : null,
            'token' => isset($record['token']) ? (string) $record['token'] : '',
            'nip' => $nip,
            'nip_label' => $nip !== '' ? $nip : 'No asignado',
            'activo' => (string) ($record['activo'] ?? '0') === '1',
            'expiracion' => $expiracion,
            'expiracion_label' => $this->formatDateLabel($expiracion),
            'creado_en' => $creadoEn,
            'creado_en_label' => $this->formatDateLabel($creadoEn),
            'status' => $status,
            'status_label' => portalAccessStatusLabel($status),
            'status_class' => portalAccessStatusBadgeClass($status),
        ];

        $empresa = [
            'id' => isset($record['empresa_id']) ? (int) $record['empresa_id'] : null,
            'nombre' => $empresaNombre,
            'numero_control' => $empresaNumeroControl,
            'estatus' => isset($record['empresa_estatus']) ? (string) $record['empresa_estatus'] : null,
            'label' => $empresaLabel !== '' ? $empresaLabel : null,
        ];

        return [
            'portal' => $portal,
            'empresa' => $empresa,
        ];
    }

    private function formatDateLabel(?string $value): string
    {
        if ($value === null) {
            return 'Sin fecha';
        }

        $value = trim($value);

        if ($value === '') {
            return 'Sin fecha';
        }

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return $value;
        }

        return date('Y-m-d H:i', $timestamp);
    }
}
