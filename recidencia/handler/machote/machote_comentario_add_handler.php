<?php
declare(strict_types=1);

use Common\Model\Database;
use Residencia\Model\Machote\MachoteComentarioModel;

require_once __DIR__ . '/../../../common/auth.php';
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/machote/MachoteComentarioModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToReview(null, ['comentario_error' => 'invalid']);
}

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT) ?: null;
$usuarioId = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT) ?: null;
$clausula  = trim((string) ($_POST['clausula'] ?? ''));
$comentario = trim((string) ($_POST['comentario'] ?? ''));

if ($machoteId === null || $machoteId <= 0 || $comentario === '') {
    redirectToReview($machoteId, ['comentario_error' => 'invalid']);
}

try {
    $connection = Database::getConnection();
    $model = new MachoteComentarioModel($connection);
    $ok = $model->addComentario($machoteId, $usuarioId ?: null, $clausula, $comentario);
} catch (\Throwable $exception) {
    error_log('Error al agregar comentario: ' . $exception->getMessage());
    redirectToReview($machoteId, ['comentario_error' => 'internal']);
}

if (!$ok) {
    redirectToReview($machoteId, ['comentario_error' => 'internal']);
}

redirectToReview($machoteId, ['comentario_status' => 'added']);

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
