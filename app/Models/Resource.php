<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'title', 'description', 'file_path',
        'file_type', 'external_url', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];
}
