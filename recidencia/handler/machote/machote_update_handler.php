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
$redirect = isset($_POST['redirect']) ? (string) $_POST['redirect'] : '';

if ($id === false || $id === null) {
    redirectWithStatus(null, 'invalid_id', $redirect);
}

$connection = Database::getConnection();
$model = new ConvenioMachoteModel($connection);

try {
    $guardado = $model->updateContent($id, $contenido);
} catch (\PDOException) {
    $guardado = false;
}

$status = $guardado ? 'saved' : 'save_error';
redirectWithStatus($id, $status, $redirect);

function redirectWithStatus(?int $machoteId, string $status, string $redirect): void
{
    $params = [];

    if ($machoteId !== null) {
        $params['id'] = $machoteId;
    }

    if ($status === 'saved') {
        $params['machote_status'] = $status;
    } else {
        $params['machote_error'] = $status;
    }

    if ($machoteId === null) {
        $target = '../../view/convenio/convenio_list.php';
    } elseif ($redirect === 'machote_revisar') {
        $target = '../../view/machote/machote_revisar.php';
    } else {
        $target = '../../view/machote/machote_edit.php';
    }

    $query = http_build_query($params);
    $url = $target . ($query !== '' ? '?' . $query : '');

    header('Location: ' . $url);
    exit;
}
