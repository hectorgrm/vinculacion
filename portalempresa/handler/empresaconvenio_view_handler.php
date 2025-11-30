<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaConvenioViewController;
use PortalEmpresa\Helpers\EmpresaConvenioHelper;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controller/EmpresaConvenioViewController.php';
require_once __DIR__ . '/../helpers/empresaconveniofunction.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);

$selectedConvenioId = null;

if (isset($_GET['id_convenio'])) {
    $maybeId = (int) $_GET['id_convenio'];

    if ($maybeId > 0) {
        $selectedConvenioId = $maybeId;
    }
}

$controller = new EmpresaConvenioViewController();
$viewState = $controller->buildViewData($empresaId, $selectedConvenioId);

$empresaData = isset($viewState['empresa']) && is_array($viewState['empresa'])
    ? $viewState['empresa']
    : null;

$convenioData = isset($viewState['convenio']) && is_array($viewState['convenio'])
    ? $viewState['convenio']
    : null;

$statusMeta = isset($viewState['status']) && is_array($viewState['status'])
    ? $viewState['status']
    : EmpresaConvenioHelper::statusMeta(null, false);

$viewErrors = isset($viewState['errors']) && is_array($viewState['errors'])
    ? $viewState['errors']
    : [];

$listaConvenios = isset($viewState['historial']) && is_array($viewState['historial'])
    ? $viewState['historial']
    : [];

$empresaNombre = $empresaData['nombre'] ?? (string) ($portalSession['empresa_nombre'] ?? 'Empresa');
$empresaBadgeVariant = $empresaData['estatus_badge_variant'] ?? EmpresaConvenioHelper::mapBadgeVariant($empresaData['estatus'] ?? ($portalSession['empresa_estatus'] ?? null));
$empresaBadgeLabel = $empresaData['estatus_label'] ?? EmpresaConvenioHelper::valueOrDefault($empresaData['estatus'] ?? ($portalSession['empresa_estatus'] ?? null), 'Sin estatus');

$empresa = $empresaData ?? [
    'nombre' => $empresaNombre,
    'logo_path' => $portalSession['empresa_logo_path'] ?? null,
];

$hasConvenio = $convenioData !== null && ($statusMeta['has_convenio'] ?? true);
$documentAvailable = $hasConvenio && ($convenioData['has_document'] ?? false);
