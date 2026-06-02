<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // If not authenticated, or running in CLI without auth, skip
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super Admins can access all records
        if ($user->isSuperAdmin()) {
            return;
        }

        // Apply tenant filter
        $builder->where($model->getTable() . '.organization_id', $user->organization_id);
    }
}
