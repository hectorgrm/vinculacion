<?php
declare(strict_types=1);

use Common\Model\Database;
use Residencia\Model\Machote\MachoteComentarioModel;

require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/machote/MachoteComentarioModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToReview(null, ['comentario_error' => 'invalid']);
}

$comentarioId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
$machoteId    = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT) ?: null;

if ($comentarioId === null || $comentarioId <= 0 || $machoteId === null || $machoteId <= 0) {
    redirectToReview($machoteId, ['comentario_error' => 'invalid']);
}

try {
    $connection = Database::getConnection();
    $model = new MachoteComentarioModel($connection);

    if ($model->machoteEstaBloqueado((int) $machoteId)) {
        redirectToReview($machoteId, ['comentario_error' => 'locked']);
    }

    $ok = $model->reabrirComentario($comentarioId);
} catch (\Throwable $exception) {
    error_log('Error al reabrir comentario: ' . $exception->getMessage());
    redirectToReview($machoteId, ['comentario_error' => 'internal']);
}

if (!$ok) {
    redirectToReview($machoteId, ['comentario_error' => 'not_found']);
}

redirectToReview($machoteId, ['comentario_status' => 'reopened']);

function redirectToReview(?int $machoteId, array $params): void
{
    if ($machoteId !== null && $machoteId > 0) {
        $params['id'] = $machoteId;
        $target = '../../view/machote/machote_revisar.php';
    } else {
        $target = '../../view/machote/machote_list.php';
    }

    $query = http_build_query($params);
    header('Location: ' . $target . ($query !== '' ? '?' . $query : ''));
    exit;
}
