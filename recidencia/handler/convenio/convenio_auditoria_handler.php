<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_auditoria.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioAuditoriaController.php';

use Residencia\Controller\Convenio\ConvenioAuditoriaController;

if (!function_exists('convenioAuditoriaFetchHistorial')) {
    /**
     * @return array{historial: array<int, array<string, string>>, error: ?string}
     */
    function convenioAuditoriaFetchHistorial(int $convenioId, ?int $empresaId = null, int $limit = 30): array
    {
        $result = convenioAuditoriaDefaults();

        try {
            $controller = new ConvenioAuditoriaController();
        } catch (\Throwable $exception) {
            $result['error'] = convenioAuditoriaControllerErrorMessage($exception);

            return $result;
        }

        $limit = convenioAuditoriaNormalizeLimit($limit);

        try {
            $records = $controller->fetchHistorial($convenioId, $empresaId, $limit);
        } catch (\Throwable $exception) {
            $result['error'] = convenioAuditoriaControllerErrorMessage($exception);

            return $result;
        }

        $result['historial'] = convenioAuditoriaDecorateHistorial($records);

        return $result;
    }
}
