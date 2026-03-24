<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'title', 'authors', 'journal',
        'year', 'abstract', 'url', 'cover_image', 'is_published',
        'access_type', 'file_path',
    ];

    protected $casts = ['is_published' => 'boolean'];
}
