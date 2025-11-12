<?php
declare(strict_types=1);

namespace PortalEmpresa\Controller;

use PortalEmpresa\Helpers\EmpresaConvenioHelper;
use PortalEmpresa\Model\EmpresaConvenioViewModel;
use PortalEmpresa\Model\Machote\MachoteViewModel;
use PortalEmpresa\Model\PortalEmpresaEmpresaModel;
use RuntimeException;

require_once __DIR__ . '/../helpers/empresaconveniofunction.php';
require_once __DIR__ . '/../model/PortalEmpresaEmpresaModel.php';
require_once __DIR__ . '/../model/EmpresaConvenioViewModel.php';
require_once __DIR__ . '/../model/MachoteViewModel.php';

class PortalEmpresaDashboardController
{
    private PortalEmpresaEmpresaModel $empresaModel;

    private EmpresaConvenioViewModel $convenioModel;

    private MachoteViewModel $machoteModel;

    public function __construct(
        ?PortalEmpresaEmpresaModel $empresaModel = null,
        ?EmpresaConvenioViewModel $convenioModel = null,
        ?MachoteViewModel $machoteModel = null
    ) {
        $this->empresaModel = $empresaModel ?? new PortalEmpresaEmpresaModel();
        $this->convenioModel = $convenioModel ?? new EmpresaConvenioViewModel();
        $this->machoteModel = $machoteModel ?? new MachoteViewModel();
    }

    /**
     * @return array{
     *     empresa: array<string, mixed>,
     *     convenio: array<string, mixed>|null,
     *     machote: array<string, mixed>|null,
     *     stats: array{comentarios_total: int, comentarios_pendientes: int, comentarios_resueltos: int, avance: int, estado_revision: string}
     * }
     */
    public function loadDashboard(int $empresaId): array
    {
        if ($empresaId <= 0) {
            throw new RuntimeException('La sesión de la empresa no es válida.');
        }

        $empresa = $this->empresaModel->findEmpresaById($empresaId);

        if ($empresa === null) {
            throw new RuntimeException('No se encontró la información de la empresa.');
        }

        $convenio = $this->convenioModel->findLatestConvenioByEmpresaId($empresaId);
        $machoteResumen = null;
        $stats = [
            'comentarios_total' => 0,
            'comentarios_pendientes' => 0,
            'comentarios_resueltos' => 0,
            'avance' => 0,
            'estado_revision' => 'Sin documento',
        ];

        if ($convenio !== null) {
            $machoteId = isset($convenio['machote_id']) ? (int) $convenio['machote_id'] : 0;

            if ($machoteId > 0) {
                $machote = $this->machoteModel->getMachoteForEmpresa($machoteId, $empresaId);

                if ($machote !== null) {
                    $comentarioStats = $this->machoteModel->getComentarioStats($machoteId);
                    $total = (int) ($comentarioStats['total'] ?? 0);
                    $pendientes = (int) ($comentarioStats['pendientes'] ?? 0);
                    $resueltos = (int) ($comentarioStats['resueltos'] ?? 0);
                    $confirmado = (int) ($machote['confirmacion_empresa'] ?? 0) === 1;

                    if ($total > 0) {
                        $avance = (int) round(($resueltos / $total) * 100);
                    } else {
                        $avance = $confirmado ? 100 : 0;
                    }

                    $avance = max(0, min(100, $avance));

                    if ($confirmado) {
                        $estadoRevision = 'Aprobado';
                    } elseif ($pendientes > 0) {
                        $estadoRevision = 'En revisión';
                    } else {
                        $estadoRevision = 'Pendiente de confirmación';
                    }

                    $pdfPrincipal = EmpresaConvenioHelper::normalizePath($machote['convenio_firmado_path'] ?? null);
                    $fuenteDocumento = null;

                    if ($pdfPrincipal !== null) {
                        $fuenteDocumento = 'firmado';
                    } else {
                        $pdfPrincipal = EmpresaConvenioHelper::normalizePath($machote['convenio_borrador_path'] ?? null);
                        if ($pdfPrincipal !== null) {
                            $fuenteDocumento = 'borrador';
                        }
                    }

                    $pdfEmbed = $pdfPrincipal;

                    if ($pdfEmbed !== null && strpos($pdfEmbed, '#') === false) {
                        $pdfEmbed .= '#view=FitH';
                    }

                    $machoteResumen = [
                        'id' => $machoteId,
                        'convenio_id' => (int) ($machote['convenio_id'] ?? 0),
                        'version' => (string) ($machote['version_local'] ?? 'v1.0'),
                        'confirmado' => $confirmado,
                        'actualizado_en' => $machote['machote_actualizado_en'] ?? null,
                        'comentarios_total' => $total,
                        'comentarios_pendientes' => $pendientes,
                        'comentarios_resueltos' => $resueltos,
                        'avance' => $avance,
                        'estado_revision' => $estadoRevision,
                        'convenio_estatus' => $machote['convenio_estatus'] ?? null,
                        'puede_confirmar' => !$confirmado && $pendientes === 0,
                        'puede_comentar' => $this->puedeAgregarComentarios($machote['convenio_estatus'] ?? null, $confirmado),
                        'documento_pdf_url' => $pdfPrincipal,
                        'documento_pdf_embed_url' => $pdfEmbed,
                        'documento_fuente' => $fuenteDocumento,
                    ];

                    $stats = [
                        'comentarios_total' => $total,
                        'comentarios_pendientes' => $pendientes,
                        'comentarios_resueltos' => $resueltos,
                        'avance' => $avance,
                        'estado_revision' => $estadoRevision,
                    ];
                }
            }
        }

        return [
            'empresa' => $empresa,
            'convenio' => $convenio,
            'machote' => $machoteResumen,
            'stats' => $stats,
        ];
    }

    private function puedeAgregarComentarios(?string $estatusConvenio, bool $machoteConfirmado): bool
    {
        if ($machoteConfirmado) {
            return false;
        }

        if ($estatusConvenio === null) {
            return false;
        }

        $estatusNormalizado = function_exists('mb_strtolower')
            ? mb_strtolower($estatusConvenio, 'UTF-8')
            : strtolower($estatusConvenio);

        return $estatusNormalizado === 'en revisión' || $estatusNormalizado === 'en revision';
    }
}
