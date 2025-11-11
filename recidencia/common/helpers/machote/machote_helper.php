<?php
declare(strict_types=1);

function machote_format_date(?string $fecha): string
{
    if (!$fecha) return '—';
    return date('d M Y', strtotime($fecha));
}

function machote_estado_badge(string $estado): string
{
    $estado = strtolower(trim($estado));
    switch ($estado) {
        case 'aprobado':
            return '<span class="badge aprobado">Aprobado</span>';
        case 'en revisión':
        case 'en revision':
            return '<span class="badge en_revision">En revisión</span>';
        case 'cancelado':
            return '<span class="badge cancelado">Cancelado</span>';
        default:
            return '<span class="badge gris">—</span>';
    }
}
