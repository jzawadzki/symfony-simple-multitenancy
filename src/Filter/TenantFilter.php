<?php


namespace App\Filter;

use App\Entity\Tenant;
use App\Tenant\TenantAware;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class TenantFilter extends SQLFilter
{
    /** @var Tenant|null */
    private $tenant;

    public function setTenant(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        if (!$this->tenant) {
            return "";
        }

        // Check if the entity implements the TenantAware interface
        if (!$targetEntity->reflClass->implementsInterface(TenantAware::class)) {
            return "";
        }

        //add sql condition
        $condition = $targetTableAlias . '.tenant_id = ' . $this->tenant->getId(); // getParameter applies quoting automatically

        return $condition;
    }
}