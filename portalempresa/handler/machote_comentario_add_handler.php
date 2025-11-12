<?php
require_once __DIR__ . '/../../../controller/machote/MachoteViewController.php';
require_once __DIR__ . '/../../../model/machote/MachoteViewModel.php';

use PortalEmpresa\Model\Machote\MachoteViewModel;

$model = new MachoteViewModel();
$machoteId = (int)$_POST['machote_id'];
$comentario = trim($_POST['comentario'] ?? '');

if ($machoteId && $comentario) {
    $model->addComentario($machoteId, $comentario, 'empresa', null);
}

header("Location: ../../view/machote_view.php?id={$machoteId}");
exit;
