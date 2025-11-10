<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/machoteglobal/MachoteGlobalModel.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';

use Common\Model\Database;
use Residencia\Model\Convenio\ConvenioMachoteModel;
use Residencia\Model\MachoteGlobal\MachoteGlobalModel;

$empresaId = filter_input(INPUT_GET, 'empresa_id', FILTER_VALIDATE_INT);
$convenioId = filter_input(INPUT_GET, 'convenio_id', FILTER_VALIDATE_INT);

if ($convenioId === false || $convenioId === null) {
    redirectToConvenio(null, 'missing_params');
}

$connection = Database::getConnection();
$convenioMachoteModel = new ConvenioMachoteModel($connection);

$existingMachote = $convenioMachoteModel->getByConvenio($convenioId);
if ($existingMachote !== null) {
    header('Location: ../../view/machote/machote_edit.php?id=' . urlencode((string) $existingMachote['id']));
    exit;
}

$machoteGlobalModel = new MachoteGlobalModel($connection);
$global = $machoteGlobalModel->getLatest();

if ($global === null) {
    redirectToConvenio($convenioId, 'no_global');
}

$contenidoHtml = isset($global['contenido_html']) ? (string) $global['contenido_html'] : '';
$machotePadreId = (int) ($global['id'] ?? 0);

if ($machotePadreId <= 0) {
    redirectToConvenio($convenioId, 'no_global');
}

try {
    $nuevoMachoteId = $convenioMachoteModel->create($convenioId, $machotePadreId, $contenidoHtml, 'v1.0');
} catch (\PDOException) {
    redirectToConvenio($convenioId, 'create_failed');
}

try {
    $statement = $connection->prepare('UPDATE rp_convenio SET machote_id = :machote_id WHERE id = :id');
    $statement->execute([
        ':machote_id' => $nuevoMachoteId,
        ':id' => $convenioId,
    ]);
} catch (\PDOException) {
    redirectToConvenio($convenioId, 'link_failed');
}

$redirectUrl = '../../view/machote/machote_edit.php?id=' . urlencode((string) $nuevoMachoteId) . '&machote_status=created';
header('Location: ' . $redirectUrl);
exit;

/**
 * @param int|null $convenioId
 */
function redirectToConvenio(?int $convenioId, string $code): void
{
    $params = [];

    if ($convenioId !== null) {
        $params['id'] = $convenioId;
    }

    $params['machote_error'] = $code;

    $query = http_build_query($params);
    $url = '../../view/convenio/convenio_view.php';
    if ($query !== '') {
        $url .= '?' . $query;
    }

    header('Location: ' . $url);
    exit;
}
