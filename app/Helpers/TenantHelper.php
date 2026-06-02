<?php

namespace App\Helpers;

class TenantHelper
{
    /**
     * Check if a given tenant ID matches the current user's tenant ID.
     *
     * @param int $tenantId
     * @return bool
     */
    public static function isCurrentTenant(int $tenantId): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->tenant_id === $tenantId;
    }
}
