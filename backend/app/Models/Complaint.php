<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'client_name',
        'description',
        'urgency_level',
        'status',
    ];

    /**
     * Get the QR code that the complaint belongs to
     */
    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    /**
     * Get the task associated with the complaint
     */
    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
}
