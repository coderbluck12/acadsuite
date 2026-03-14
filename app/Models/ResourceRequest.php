<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class ResourceRequest extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'first_name', 'last_name',
        'email', 'phone', 'message', 'is_read',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
