<?php

declare(strict_types=1);

namespace App\Services\Facturacion\DTO;

final class NubeFactResponseDTO
{
    public function __construct(
        private array $data
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function aceptada(): bool
    {
        return true;
    }

    public function mensaje(): string
    {
        return $this->data['sunat_description'] ?? 'OK';
    }

    public function serie(): ?string
    {
        return $this->data['serie'] ?? null;
    }

    public function numero(): ?string
    {
        return isset($this->data['numero'])
            ? (string) $this->data['numero']
            : null;
    }

    public function descripcion(): ?string
    {
        return $this->data['sunat_description'] ?? null;
    }

    public function pdf(): ?string
    {
        return $this->data['enlace_del_pdf'] ?? null;
    }

    public function xml(): ?string
    {
        return $this->data['enlace_del_xml'] ?? null;
    }

    public function cdr(): ?string
    {
        return $this->data['enlace_del_cdr'] ?? null;
    }

    public function response(): array
    {
        return $this->data;
    }
}