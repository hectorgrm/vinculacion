<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../ConvenioController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';

use Residencia\Controller\ConvenioController;
use Throwable;

class ConvenioListController
{
    /**
     * @param array<string, mixed> $query
     * @return array{
     *     search: string,
     *     selectedStatus: string,
     *     statusOptions: array<int, string>,
     *     convenios: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $query): array
    {
        $search = isset($query['search']) ? trim((string) $query['search']) : '';
        $rawStatus = isset($query['estatus']) ? trim((string) $query['estatus']) : '';

        $estatusFilter = $rawStatus !== '' ? convenioNormalizeStatus($rawStatus) : null;
        $selectedStatus = $estatusFilter ?? '';

        $convenios = [];
        $errorMessage = null;

        try {
            $controller = new ConvenioController();
            $convenios = $controller->listConvenios($search !== '' ? $search : null, $estatusFilter);
        } catch (Throwable $exception) {
            $errorMessage = $exception->getMessage();
        }

        return [
            'search' => $search,
            'selectedStatus' => $selectedStatus,
            'statusOptions' => convenioStatusOptions(),
            'convenios' => $convenios,
            'errorMessage' => $errorMessage,
        ];
    }
}
