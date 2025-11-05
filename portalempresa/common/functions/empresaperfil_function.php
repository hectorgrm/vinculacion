<?php

declare(strict_types=1);

require_once __DIR__ . '/../../helpers/empresaperfil_helper_function.php';

if (!function_exists('empresaPerfilEmptyRecord')) {
    /**
     * @return array<string, mixed>
     */
    function empresaPerfilEmptyRecord(): array
    {
        return [
            'id' => 0,
            'numero_control' => '',
            'nombre' => '',
            'rfc' => '',
            'representante' => '',
            'cargo_representante' => '',
            'sector' => '',
            'sitio_web' => null,
            'sitio_web_url' => null,
            'contacto_nombre' => '',
            'contacto_email' => '',
            'telefono' => '',
            'estado' => '',
            'municipio' => '',
            'cp' => '',
            'direccion' => '',
            'estatus' => 'En revisión',
            'regimen_fiscal' => null,
            'notas' => null,
            'creado_en' => null,
            'actualizado_en' => null,
        ];
    }
}

if (!function_exists('empresaPerfilHydrateRecord')) {
    /**
     * @param array<string, mixed>|null $record
     * @return array<string, mixed>
     */
    function empresaPerfilHydrateRecord(?array $record): array
    {
        $defaults = empresaPerfilEmptyRecord();

        if ($record === null) {
            return $defaults;
        }

        $nombre = empresaPerfilNormalizeString(isset($record['nombre']) ? (string) $record['nombre'] : null);
        $numeroControl = empresaPerfilNormalizeString(isset($record['numero_control']) ? (string) $record['numero_control'] : null);
        $rfc = empresaPerfilNormalizeString(isset($record['rfc']) ? (string) $record['rfc'] : null);
        $representante = empresaPerfilNormalizeString(isset($record['representante']) ? (string) $record['representante'] : null);
        $cargoRepresentante = empresaPerfilNormalizeString(isset($record['cargo_representante']) ? (string) $record['cargo_representante'] : null);
        $sector = empresaPerfilNormalizeString(isset($record['sector']) ? (string) $record['sector'] : null);
        $sitioWeb = empresaPerfilNormalizeNullableString(isset($record['sitio_web']) ? (string) $record['sitio_web'] : null);
        $contactoNombre = empresaPerfilNormalizeString(isset($record['contacto_nombre']) ? (string) $record['contacto_nombre'] : null);
        $contactoEmail = empresaPerfilNormalizeString(isset($record['contacto_email']) ? (string) $record['contacto_email'] : null);
        $telefono = empresaPerfilNormalizeString(isset($record['telefono']) ? (string) $record['telefono'] : null);
        $estado = empresaPerfilNormalizeString(isset($record['estado']) ? (string) $record['estado'] : null);
        $municipio = empresaPerfilNormalizeString(isset($record['municipio']) ? (string) $record['municipio'] : null);
        $cp = empresaPerfilNormalizeString(isset($record['cp']) ? (string) $record['cp'] : null);
        $direccion = empresaPerfilNormalizeString(isset($record['direccion']) ? (string) $record['direccion'] : null);
        $estatus = empresaPerfilNormalizeStatus(isset($record['estatus']) ? (string) $record['estatus'] : null);
        $regimen = empresaPerfilNormalizeNullableString(isset($record['regimen_fiscal']) ? (string) $record['regimen_fiscal'] : null);
        $notas = empresaPerfilNormalizeNullableString(isset($record['notas']) ? (string) $record['notas'] : null);

        return [
            'id' => isset($record['id']) ? (int) $record['id'] : 0,
            'numero_control' => $numeroControl,
            'nombre' => $nombre,
            'rfc' => $rfc,
            'representante' => $representante,
            'cargo_representante' => $cargoRepresentante,
            'sector' => $sector,
            'sitio_web' => $sitioWeb,
            'sitio_web_url' => $sitioWeb !== null ? empresaPerfilFormatWebsite($sitioWeb) : null,
            'contacto_nombre' => $contactoNombre,
            'contacto_email' => $contactoEmail,
            'telefono' => $telefono,
            'estado' => $estado,
            'municipio' => $municipio,
            'cp' => $cp,
            'direccion' => $direccion,
            'estatus' => $estatus,
            'regimen_fiscal' => $regimen,
            'notas' => $notas,
            'creado_en' => $record['creado_en'] ?? null,
            'actualizado_en' => $record['actualizado_en'] ?? null,
        ];
    }
}
