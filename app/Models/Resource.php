<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'course_id', 'title', 'description', 'file_path',
        'file_type', 'external_url', 'is_published', 'is_general',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_general' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
