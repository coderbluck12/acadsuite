<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        // Auto-apply tenant scope on all queries
        static::addGlobalScope('tenant', function (Builder $query) {
            if (app()->has('currentTenant')) {
                $query->where('tenant_id', app('currentTenant')->id);
            }
        });

        // Auto-fill tenant_id on create
        static::creating(function ($model) {
            if (app()->has('currentTenant') && empty($model->tenant_id)) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
