<?php


namespace App\Tenant;


use App\Entity\Tenant;

interface TenantAware
{
    public function getTenant(): ?Tenant;

    public function setTenant(Tenant $tenant);
}