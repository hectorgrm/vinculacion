<?php
require_once __DIR__ . '/../../../model/machote/MachoteViewModel.php';

use PortalEmpresa\Model\Machote\MachoteViewModel;

$model = new MachoteViewModel();
$machoteId = (int)$_POST['machote_id'];

if ($machoteId) {
    $model->confirmarMachote($machoteId);
}

header("Location: ../../view/machote_view.php?id={$machoteId}");
exit;
