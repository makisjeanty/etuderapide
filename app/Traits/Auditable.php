<?php

namespace App\Traits;

use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the trait and register Eloquent events.
     */
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            self::logAction('created', $model);
        });

        static::updated(function (Model $model) {
            self::logAction('updated', $model);
        });

        static::deleted(function (Model $model) {
            self::logAction('deleted', $model);
        });
    }

    /**
     * Record the action to the audit log.
     */
    protected static function logAction(string $action, Model $model): void
    {
        $sensitiveFields = ['password', 'remember_token', 'api_token', 'access_token', 'secret', 'key', 'token', 'pepper'];
        $properties = $model->getDirty() ?: $model->toArray();

        // Remove campos sensíveis das propriedades logadas
        foreach ($sensitiveFields as $field) {
            unset($properties[$field]);
            // Também remove variações (ex: current_password)
            foreach (array_keys($properties) as $key) {
                if (str_contains(strtolower($key), $field)) {
                    unset($properties[$key]);
                }
            }
        }

        AuditLogger::record(
            Auth::user(),
            "{$model->getTable()}.{$action}",
            get_class($model),
            $model->id,
            $properties
        );
    }
}
