<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait Multitenantable
{
    /**
     * Boot the multitenantable trait for a model.
     *
     * @return void
     */
    protected static function bootMultitenantable()
    {
        // Register the global scope
        static::addGlobalScope(new TenantScope());

        // Automatically assign organization_id on creation
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                
                // If a super admin creates a record, they might manually set the organization_id.
                // We only automatically assign if it's not set.
                if (!$model->organization_id) {
                    $model->organization_id = $user->organization_id;
                }
            }
        });
    }
}
