<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaConvenioViewController;
use PortalEmpresa\Helpers\EmpresaConvenioHelper;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controller/EmpresaConvenioViewController.php';
require_once __DIR__ . '/../helpers/empresaconveniofunction.php';

if (!isset($_SESSION['portal_empresa']) || !is_array($_SESSION['portal_empresa'])) {
    header('Location: ../view/login.php?error=session');
    exit;
}

$portalSession = $_SESSION['portal_empresa'];
$empresaId = isset($portalSession['empresa_id']) ? (int) $portalSession['empresa_id'] : 0;

if ($empresaId <= 0) {
    header('Location: ../view/login.php?error=session');
    exit;
}

$controller = new EmpresaConvenioViewController();
$viewState = $controller->buildViewData($empresaId);

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

$empresaNombre = $empresaData['nombre'] ?? (string) ($portalSession['empresa_nombre'] ?? 'Empresa');
$empresaBadgeVariant = $empresaData['estatus_badge_variant'] ?? EmpresaConvenioHelper::mapBadgeVariant($empresaData['estatus'] ?? ($portalSession['empresa_estatus'] ?? null));
$empresaBadgeLabel = $empresaData['estatus_label'] ?? EmpresaConvenioHelper::valueOrDefault($empresaData['estatus'] ?? ($portalSession['empresa_estatus'] ?? null), 'Sin estatus');

$hasConvenio = $convenioData !== null && ($statusMeta['has_convenio'] ?? true);
$documentAvailable = $hasConvenio && ($convenioData['has_document'] ?? false);
