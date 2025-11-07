<?php
declare(strict_types=1);

/**
 * Builds the student's display name by concatenating name and surnames.
 */
function estudiante_list_format_nombre(array $registro): string
{
    $nombre = trim((string) ($registro['nombre'] ?? ''));
    $apellidoP = trim((string) ($registro['apellido_paterno'] ?? ''));
    $apellidoM = trim((string) ($registro['apellido_materno'] ?? ''));

    return trim(sprintf('%s %s %s', $nombre, $apellidoP, $apellidoM));
}

/**
 * Returns the CSS class used to render the status badge.
 */
function estudiante_list_badge_class(string $estatus): string
{
    return match (strtolower($estatus)) {
        'activo' => 'badge activo',
        'finalizado' => 'badge finalizado',
        'inactivo' => 'badge inactivo',
        default => 'badge',
    };
}

/**
 * Normalises the status label shown to the user.
 */
function estudiante_list_badge_label(?string $estatus): string
{
    $estatus = $estatus ?? '';

    return match ($estatus) {
        'Activo' => 'Activo',
        'Finalizado' => 'Finalizado',
        'Inactivo' => 'Inactivo',
        default => 'Sin estatus',
    };
}
