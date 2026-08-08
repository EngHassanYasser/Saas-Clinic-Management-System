<?php

namespace App\Support;

use App\Models\Clinic;
use RuntimeException;

class TenantContext
{
    private ?Clinic $clinic = null;

    public function set(Clinic $clinic): void
    {
        $this->clinic = $clinic;
    }

    public function get(): Clinic
    {
        if (! $this->clinic) {
            throw new RuntimeException('Tenant context has not been initialized.');
        }

        return $this->clinic;
    }

    public function id(): int
    {
        return $this->get()->id;
    }

    public function has(): bool
    {
        return $this->clinic !== null;
    }

    public function clear(): void
    {
        $this->clinic = null;
    }
}