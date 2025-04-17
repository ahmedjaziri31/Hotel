<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor',
        'type',
        'status',
    ];

    /**
     * Get QR codes for the room
     */
    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }
}
