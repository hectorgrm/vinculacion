<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioModle.php';
require_once __DIR__ . '/../../common/helpers/convenio/convenio_helper_renovar.php';

use PDOException;
use Residencia\Model\Convenio\ConvenioModle;
use RuntimeException;
use Throwable;

class ConvenioRenovarController
{
    private ConvenioModle $model;

    public function __construct(?ConvenioModle $model = null)
    {
        $this->model = $model ?? new ConvenioModle();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findOriginalConvenio(int $convenioId): ?array
    {
        try {
            return $this->model->findForRenewal($convenioId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la información del convenio seleccionado.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createRenewal(array $payload): int
    {
        try {
            return $this->model->createRenewal($payload);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo generar la nueva versión del convenio.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $get
     * @param array<string, mixed> $post
     * @param array<string, mixed> $server
     * @return array{
     *     controllerAvailable: bool,
     *     controllerError: ?string,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     formData: array<string, string>,
     *     originalConvenio: ?array,
     *     originalMetadata: array<string, string>,
     *     empresaId: ?int,
     *     empresaLink: ?string,
     *     cancelLink: string,
     *     listLink: string,
     *     copyId: ?int,
     *     renewalAllowed: bool,
     *     renewalWarning: ?string,
     *     allowedStatuses: array<int, string>,
     *     newConvenioId: ?int,
     *     newConvenioUrl: ?string
     * }
     */
    public function handle(array $get, array $post, array $server): array
    {
        $method = $this->resolveMethod($server);
        $copyId = $this->resolveConvenioId($get, $post, $method);
        $empresaIdQuery = $this->resolveEmpresaId($get);

        $errors = [];
        $successMessage = null;
        $controllerError = null;
        $controllerAvailable = true;
        $original = null;
        $renewalAllowed = false;
        $renewalWarning = null;
        $newConvenioId = null;
        $newConvenioUrl = null;

        if ($copyId === null) {
            $controllerAvailable = false;
            $controllerError = 'Selecciona un convenio válido para poder generar una renovación.';
        }

        if ($controllerAvailable && $copyId !== null) {
            try {
                $original = $this->findOriginalConvenio($copyId);
            } catch (RuntimeException $exception) {
                $controllerAvailable = false;
                $controllerError = $exception->getMessage();
            }

            if ($controllerAvailable && $original === null) {
                $controllerAvailable = false;
                $controllerError = 'No se encontró la información del convenio solicitado.';
            }
        }

        if ($original !== null) {
            $renewalAllowed = convenioRenewalIsStatusAllowed(isset($original['estatus']) ? (string) $original['estatus'] : null);

            if (!$renewalAllowed) {
                $renewalWarning = 'Solo es posible renovar convenios con estatus "Activa" o "Completado".';
            }
        }

        $formData = convenioRenewalFormDefaults($original);

        if ($method === 'POST') {
            if (!$controllerAvailable) {
                $errors[] = $controllerError ?? 'No se pudo procesar la solicitud de renovación.';
            } elseif ($original === null || $copyId === null) {
                $errors[] = 'El convenio seleccionado no es válido.';
            } elseif (!$renewalAllowed) {
                $errors[] = $renewalWarning ?? 'El convenio seleccionado no permite generar una renovación.';
            } else {
                $formData = convenioRenewalSanitizeInput($post, $original);
                $errors = convenioRenewalValidate($formData);

                if ($errors === []) {
                    $currentStatusAllowed = convenioRenewalIsStatusAllowed(isset($original['estatus']) ? (string) $original['estatus'] : null);

                    if (!$currentStatusAllowed) {
                        $errors[] = 'El convenio cambió de estatus y ya no puede renovarse.';
                    }
                }

                if ($errors === []) {
                    try {
                        $payload = convenioRenewalBuildPayload($formData, $original);
                        $newConvenioId = $this->createRenewal($payload);
                        $successMessage = 'Se generó correctamente la nueva versión del convenio (#' . $newConvenioId . ').';
                        $newConvenioUrl = 'convenio_view.php?id=' . urlencode((string) $newConvenioId);
                    } catch (RuntimeException $exception) {
                        $errors[] = $exception->getMessage() !== ''
                            ? $exception->getMessage()
                            : 'Ocurrió un error al generar la renovación. Intenta nuevamente.';
                    } catch (Throwable) {
                        $errors[] = 'Ocurrió un error inesperado al generar la renovación.';
                    }
                }
            }
        }

        if ($errors !== []) {
            $errors = array_values(array_unique($errors));
        }

        $empresaId = null;
        $empresaLink = null;

        if ($original !== null && isset($original['empresa_id'])) {
            $empresaId = (int) $original['empresa_id'];
        } elseif ($empresaIdQuery !== null) {
            $empresaId = $empresaIdQuery;
        }

        if ($empresaId !== null) {
            $empresaLink = '../empresa/empresa_view.php?id=' . urlencode((string) $empresaId);
        }

        $cancelLink = $copyId !== null
            ? 'convenio_view.php?id=' . urlencode((string) $copyId)
            : 'convenio_list.php';

        $originalMetadata = convenioRenewalPrepareOriginalMetadata($original);

        return [
            'controllerAvailable' => $controllerAvailable,
            'controllerError' => $controllerError,
            'errors' => $errors,
            'successMessage' => $successMessage,
            'formData' => $formData,
            'originalConvenio' => $original,
            'originalMetadata' => $originalMetadata,
            'empresaId' => $empresaId,
            'empresaLink' => $empresaLink,
            'cancelLink' => $cancelLink,
            'listLink' => 'convenio_list.php',
            'copyId' => $copyId,
            'renewalAllowed' => $renewalAllowed,
            'renewalWarning' => $renewalWarning,
            'allowedStatuses' => convenioRenewalAllowedStatuses(),
            'newConvenioId' => $newConvenioId,
            'newConvenioUrl' => $newConvenioUrl,
        ];
    }

    /**
     * @param array<string, mixed> $server
     */
    private function resolveMethod(array $server): string
    {
        $method = isset($server['REQUEST_METHOD'])
            ? strtoupper((string) $server['REQUEST_METHOD'])
            : 'GET';

        return $method !== '' ? $method : 'GET';
    }

    /**
     * @param array<string, mixed> $get
     * @param array<string, mixed> $post
     */
    private function resolveConvenioId(array $get, array $post, string $method): ?int
    {
        if ($method === 'POST') {
            if (isset($post['copy_id']) && is_scalar($post['copy_id'])) {
                $filtered = preg_replace('/[^0-9]/', '', (string) $post['copy_id']);

                if ($filtered !== null && $filtered !== '') {
                    return (int) $filtered;
                }
            }
        }

        if (isset($get['copy']) && is_scalar($get['copy'])) {
            $filtered = preg_replace('/[^0-9]/', '', (string) $get['copy']);

            if ($filtered !== null && $filtered !== '') {
                return (int) $filtered;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $get
     */
    private function resolveEmpresaId(array $get): ?int
    {
        if (!isset($get['empresa']) || !is_scalar($get['empresa'])) {
            return null;
        }

        $filtered = preg_replace('/[^0-9]/', '', (string) $get['empresa']);

        if ($filtered === null || $filtered === '') {
            return null;
        }

        return (int) $filtered;
    }
}
