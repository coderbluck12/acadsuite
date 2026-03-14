<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'title', 'content', 'cover_image',
        'slug', 'is_published', 'published_at',
    ];

    protected $casts = [
        'is_published'  => 'boolean',
        'published_at'  => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 150);
    }
}
