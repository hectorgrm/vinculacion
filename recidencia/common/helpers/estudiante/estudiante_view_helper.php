<?php

declare(strict_types=1);

function estudiante_view_format_nombre(?array $estudiante): string
{
    if (!is_array($estudiante)) {
        return 'Sin nombre registrado';
    }

    $nombre = trim((string) ($estudiante['nombre'] ?? ''));
    $apellidoPaterno = trim((string) ($estudiante['apellido_paterno'] ?? ''));
    $apellidoMaterno = trim((string) ($estudiante['apellido_materno'] ?? ''));

    $completo = trim(sprintf('%s %s %s', $nombre, $apellidoPaterno, $apellidoMaterno));

    return $completo !== '' ? $completo : 'Sin nombre registrado';
}

function estudiante_view_badge_class(?string $estatus): string
{
    $estatus = strtolower(trim((string) ($estatus ?? '')));

    return match ($estatus) {
        'activo' => 'badge activo',
        'finalizado' => 'badge finalizado',
        'inactivo' => 'badge inactivo',
        default => 'badge secondary',
    };
}

function estudiante_view_badge_label(?string $estatus): string
{
    return match ($estatus ?? '') {
        'Activo' => 'Activo',
        'Finalizado' => 'Finalizado',
        'Inactivo' => 'Inactivo',
        default => 'Sin estatus',
    };
}

function estudiante_view_convenio_badge_class(?string $estatus): string
{
    $estatus = strtolower(trim((string) ($estatus ?? '')));

    return match ($estatus) {
        'activa' => 'badge activo',
        'completado' => 'badge finalizado',
        'inactiva', 'suspendida' => 'badge inactivo',
        'en revisión', 'en revision' => 'badge secondary',
        default => 'badge secondary',
    };
}

function estudiante_view_convenio_badge_label(?string $estatus): string
{
    return match ($estatus ?? '') {
        'Activa' => 'Activa',
        'En revisión' => 'En revisión',
        'Inactiva' => 'Inactiva',
        'Suspendida' => 'Suspendida',
        'Completado' => 'Completado',
        default => 'Sin convenio',
    };
}

function estudiante_view_format_datetime(?string $value): string
{
    if ($value === null) {
        return 'N/D';
    }

    $value = trim($value);
    if ($value === '') {
        return 'N/D';
    }

    try {
        $date = new DateTimeImmutable($value);
    } catch (Throwable) {
        return $value;
    }

    return $date->format('d/m/Y H:i');
}

function estudiante_view_format_date(?string $value): string
{
    if ($value === null) {
        return 'N/D';
    }

    $value = trim($value);
    if ($value === '') {
        return 'N/D';
    }

    try {
        $date = new DateTimeImmutable($value);
    } catch (Throwable) {
        return $value;
    }

    return $date->format('d/m/Y');
}

function estudiante_view_format_telefono(?string $telefono): string
{
    $telefono = trim((string) ($telefono ?? ''));

    return $telefono !== '' ? $telefono : 'N/D';
}

function estudiante_view_format_email(?string $correo): string
{
    $correo = trim((string) ($correo ?? ''));

    return $correo !== '' ? $correo : 'N/D';
}

function estudiante_view_format_text(?string $valor): string
{
    $valor = trim((string) ($valor ?? ''));

    return $valor !== '' ? $valor : 'N/D';
}

function estudiante_view_format_direccion(?array $empresa): string
{
    if (!is_array($empresa)) {
        return 'N/D';
    }

    $componentes = array_filter([
        trim((string) ($empresa['direccion'] ?? '')),
        trim((string) ($empresa['municipio'] ?? '')),
        trim((string) ($empresa['estado'] ?? '')),
        trim((string) ($empresa['cp'] ?? '')),
    ], static fn(string $value): bool => $value !== '');

    if ($componentes === []) {
        return 'N/D';
    }

    return implode(', ', $componentes);
}

/**
 * @return array{
 *     nombreCompleto: string,
 *     matricula: string,
 *     carrera: string,
 *     correoInstitucional: string,
 *     telefono: string,
 *     estatusBadgeClass: string,
 *     estatusBadgeLabel: string,
 *     creadoEnLabel: string,
 *     empresaNombre: string,
 *     empresaContactoNombre: string,
 *     empresaContactoEmail: string,
 *     empresaTelefono: string,
 *     empresaDireccion: string,
 *     empresaRepresentante: string,
 *     convenioExiste: bool,
 *     convenioFolio: string,
 *     convenioBadgeClass: string,
 *     convenioBadgeLabel: string,
 *     convenioTipo: string,
 *     convenioFechaInicio: string,
 *     convenioFechaFin: string,
 *     convenioResponsableAcademico: string
 * }
 */
function estudiante_view_prepare_metadata(?array $estudiante, ?array $empresa, ?array $convenio): array
{
    $estatus = is_array($estudiante) && array_key_exists('estatus', $estudiante)
        ? (string) $estudiante['estatus']
        : null;

    $matricula = is_array($estudiante) && array_key_exists('matricula', $estudiante)
        ? $estudiante['matricula']
        : null;
    $carrera = is_array($estudiante) && array_key_exists('carrera', $estudiante)
        ? $estudiante['carrera']
        : null;
    $correoInstitucional = is_array($estudiante) && array_key_exists('correo_institucional', $estudiante)
        ? $estudiante['correo_institucional']
        : null;
    $telefono = is_array($estudiante) && array_key_exists('telefono', $estudiante)
        ? $estudiante['telefono']
        : null;
    $creadoEn = is_array($estudiante) && array_key_exists('creado_en', $estudiante)
        ? $estudiante['creado_en']
        : null;

    $empresaNombre = is_array($empresa) && array_key_exists('nombre', $empresa)
        ? $empresa['nombre']
        : null;
    $empresaContactoNombre = is_array($empresa) && array_key_exists('contacto_nombre', $empresa)
        ? $empresa['contacto_nombre']
        : null;
    $empresaContactoEmail = is_array($empresa) && array_key_exists('contacto_email', $empresa)
        ? $empresa['contacto_email']
        : null;
    $empresaTelefono = is_array($empresa) && array_key_exists('telefono', $empresa)
        ? $empresa['telefono']
        : null;
    $empresaRepresentante = is_array($empresa) && array_key_exists('representante', $empresa)
        ? $empresa['representante']
        : null;

    $convenioFolio = is_array($convenio) && array_key_exists('folio', $convenio)
        ? $convenio['folio']
        : null;
    $convenioEstatus = is_array($convenio) && array_key_exists('estatus', $convenio)
        ? $convenio['estatus']
        : null;
    $convenioTipo = is_array($convenio) && array_key_exists('tipo_convenio', $convenio)
        ? $convenio['tipo_convenio']
        : null;
    $convenioFechaInicio = is_array($convenio) && array_key_exists('fecha_inicio', $convenio)
        ? $convenio['fecha_inicio']
        : null;
    $convenioFechaFin = is_array($convenio) && array_key_exists('fecha_fin', $convenio)
        ? $convenio['fecha_fin']
        : null;
    $convenioResponsable = is_array($convenio) && array_key_exists('responsable_academico', $convenio)
        ? $convenio['responsable_academico']
        : null;

    $metadata = [
        'nombreCompleto' => estudiante_view_format_nombre($estudiante),
        'matricula' => estudiante_view_format_text($matricula),
        'carrera' => estudiante_view_format_text($carrera),
        'correoInstitucional' => estudiante_view_format_email($correoInstitucional),
        'telefono' => estudiante_view_format_telefono($telefono),
        'estatusBadgeClass' => estudiante_view_badge_class($estatus),
        'estatusBadgeLabel' => estudiante_view_badge_label($estatus),
        'creadoEnLabel' => estudiante_view_format_datetime($creadoEn),
        'empresaNombre' => estudiante_view_format_text($empresaNombre),
        'empresaContactoNombre' => estudiante_view_format_text($empresaContactoNombre),
        'empresaContactoEmail' => estudiante_view_format_email($empresaContactoEmail),
        'empresaTelefono' => estudiante_view_format_telefono($empresaTelefono),
        'empresaDireccion' => estudiante_view_format_direccion($empresa),
        'empresaRepresentante' => estudiante_view_format_text($empresaRepresentante),
        'convenioExiste' => is_array($convenio),
        'convenioFolio' => estudiante_view_format_text($convenioFolio),
        'convenioBadgeClass' => estudiante_view_convenio_badge_class($convenioEstatus),
        'convenioBadgeLabel' => estudiante_view_convenio_badge_label($convenioEstatus),
        'convenioTipo' => estudiante_view_format_text($convenioTipo),
        'convenioFechaInicio' => estudiante_view_format_date($convenioFechaInicio),
        'convenioFechaFin' => estudiante_view_format_date($convenioFechaFin),
        'convenioResponsableAcademico' => estudiante_view_format_text($convenioResponsable),
    ];

    if ($metadata['convenioExiste'] === false) {
        $metadata['convenioBadgeClass'] = 'badge secondary';
        $metadata['convenioBadgeLabel'] = 'Sin convenio asignado';
        $metadata['convenioFolio'] = 'N/D';
        $metadata['convenioTipo'] = 'N/D';
        $metadata['convenioFechaInicio'] = 'N/D';
        $metadata['convenioFechaFin'] = 'N/D';
        $metadata['convenioResponsableAcademico'] = 'N/D';
    }

    return $metadata;
}
