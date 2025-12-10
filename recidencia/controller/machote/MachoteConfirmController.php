<?php

declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/machote/MachoteConfirmModel.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';
require_once __DIR__ . '/../../common/helpers/machote/machote_placeholders_helper.php';

use Residencia\Model\Machote\MachoteConfirmModel;
use RuntimeException;
use function auditoriaObtenerIP;
use function auditoriaRegistrarEvento;

final class MachoteConfirmController
{
    private MachoteConfirmModel $model;

    public function __construct(?MachoteConfirmModel $model = null)
    {
        $this->model = $model ?? MachoteConfirmModel::createWithDefaultConnection();
    }

    /**
     * @return array{status: 'confirmed'|'already'|'pending', pendientes?: int}
     */
    public function confirmarDesdeEmpresa(int $machoteId, int $empresaId): array
    {
        if ($machoteId <= 0 || $empresaId <= 0) {
            throw new RuntimeException('Parámetros inválidos para confirmar el machote.');
        }

        $machote = $this->model->findMachoteForEmpresa($machoteId, $empresaId);

        if ($machote === null) {
            throw new RuntimeException('Machote no disponible.');
        }

        if ((int) ($machote['confirmacion_empresa'] ?? 0) === 1) {
            return ['status' => 'already'];
        }

        if (!$this->estatusConvenioActivo($machote['convenio_estatus'] ?? null)) {
            return ['status' => 'locked'];
        }

        $pendientes = $this->model->countComentariosPendientes($machoteId);

        if ($pendientes > 0) {
            return [
                'status' => 'pending',
                'pendientes' => $pendientes,
            ];
        }

        $template = (string) ($machote['contenido_html'] ?? '');
        $empresa = [
            'nombre' => $machote['empresa_nombre'] ?? '',
            'representante' => $machote['empresa_representante'] ?? '',
            'cargo_representante' => $machote['empresa_cargo'] ?? '',
            'direccion' => $machote['empresa_direccion'] ?? '',
            'municipio' => $machote['empresa_municipio'] ?? '',
            'estado' => $machote['empresa_estado'] ?? '',
            'cp' => $machote['empresa_cp'] ?? '',
        ];
        $finalHtml = renderMachoteConEmpresa($template, $empresa);

        $this->model->confirmarMachote($machoteId, $finalHtml);
        $this->registrarAuditoriaConfirmacion($machote, $empresaId);

        return ['status' => 'confirmed'];
    }

    /**
     * @param array<string, mixed> $machote
     */
    private function registrarAuditoriaConfirmacion(array $machote, int $empresaId): void
    {
        if (!function_exists('auditoriaRegistrarEvento')) {
            return;
        }

        $machoteId = isset($machote['id']) ? (int) $machote['id'] : 0;

        if ($machoteId <= 0) {
            return;
        }

        $detalles = [];

        $estadoAnterior = (int) ($machote['confirmacion_empresa'] ?? 0) === 1
            ? 'Confirmado'
            : 'No confirmado';

        $detalles[] = [
            'campo' => 'confirmacion_empresa',
            'campo_label' => 'Confirmacion de empresa',
            'valor_anterior' => $estadoAnterior,
            'valor_nuevo' => 'Confirmado',
        ];

        $estatusAnterior = isset($machote['estatus']) ? trim((string) $machote['estatus']) : '';

        if ($estatusAnterior !== '') {
            $detalles[] = [
                'campo' => 'estatus',
                'campo_label' => 'Estatus del machote',
                'valor_anterior' => $estatusAnterior,
                'valor_nuevo' => 'Aprobado',
            ];
        }

        $payload = [
            'accion' => 'confirmar',
            'entidad' => 'rp_convenio_machote',
            'entidad_id' => $machoteId,
            'actor_tipo' => 'empresa',
            'actor_id' => $empresaId,
            'detalles' => $detalles,
        ];

        $ip = auditoriaObtenerIP();

        if ($ip !== '') {
            $payload['ip'] = $ip;
        }

        auditoriaRegistrarEvento($payload);
    }

    private function estatusConvenioActivo(?string $estatus): bool
    {
        if ($estatus === null) {
            return false;
        }

        $normalizado = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], (string) $estatus);
        $normalizado = function_exists('mb_strtolower') ? mb_strtolower($normalizado, 'UTF-8') : strtolower($normalizado);
        $normalizado = trim($normalizado);

        if ($normalizado === '') {
            return false;
        }

        if (str_contains($normalizado, 'activa')) {
            return true;
        }

        return str_contains($normalizado, 'revisi');
    }
}
