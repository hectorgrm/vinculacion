<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';

use Common\Model\Database;
use Residencia\Model\Convenio\ConvenioMachoteModel;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../view/convenio/convenio_list.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$contenido = isset($_POST['contenido']) ? (string) $_POST['contenido'] : '';

if ($id === false || $id === null) {
    redirectWithStatus(null, 'invalid_id');
}

$connection = Database::getConnection();
$model = new ConvenioMachoteModel($connection);

try {
    $guardado = $model->updateContent($id, $contenido);
} catch (\PDOException) {
    $guardado = false;
}

$status = $guardado ? 'saved' : 'save_error';
redirectWithStatus($id, $status);

function redirectWithStatus(?int $machoteId, string $status): void
{
    if ($machoteId === null) {
        header('Location: ../../view/convenio/convenio_list.php');
        exit;
    }

    $params = [
        'id' => $machoteId,
        $status === 'saved' ? 'guardado' : 'error' => $status,
    ];

    header('Location: ../../view/machote/machote_edit.php?' . http_build_query($params));
    exit;
}
