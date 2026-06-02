<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Model;

class AuditLogObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        app(AuditLogService::class)->log(
            'created',
            $model,
            [],
            $model->getAttributes()
        );
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $original = $model->getOriginal();
        $changes = $model->getChanges();
        app(AuditLogService::class)->log(
            'updated',
            $model,
            $original,
            $changes
        );
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $original = $model->getOriginal();
        app(AuditLogService::class)->log(
            'deleted',
            $model,
            $original,
            []
        );
    }
}
?>
