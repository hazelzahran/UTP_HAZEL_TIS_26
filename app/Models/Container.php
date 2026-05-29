<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    use HasFactory;

    protected $table = 'containers';

    protected $fillable = [
        'kode_container',
        'jenis_limbah',
        'kapasitas',
        'lokasi',
        'status',
    ];

    /**
     * Relasi: Satu container memiliki banyak tracking logs.
     */
    public function trackingLogs(): HasMany
    {
        return $this->hasMany(TrackingLog::class);
    }
}