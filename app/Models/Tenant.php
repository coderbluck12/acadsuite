<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'subdomain',
        'owner_name',
        'email',
        'phone',
        'bio',
        'avatar',
        'logo',
        'dashboard_bg_image',
        'home_bg_image',
        'orcid_url',
        'address',
        'social_links',
        'plan',
        'is_active',
        'approved_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active'    => 'boolean',
        'approved_at'  => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function resourceRequests(): HasMany
    {
        return $this->hasMany(ResourceRequest::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /** Get the tenant's admin user */
    public function admin()
    {
        return $this->users()->where('role', 'admin')->first();
    }

    /** Get portal URL */
    public function getPortalUrlAttribute(): string
    {
        return 'http://' . $this->subdomain . '.' . config('app.base_domain');
    }
}
