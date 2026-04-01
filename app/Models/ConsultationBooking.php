<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationBooking extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'consultation_id',
        'user_id',
        'booking_date',
        'booking_time',
        'amount_paid',
        'payment_reference',
        'status',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'booking_date' => 'date',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
