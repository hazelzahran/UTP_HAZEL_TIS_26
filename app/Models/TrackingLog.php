<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingLog extends Model
{
    use HasFactory;

    protected $table = 'tracking_logs';

    protected $fillable = [
        'container_id',
        'tanggal',
        'lokasi',
        'catatan',
        'operator',
        'status_perjalanan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Relasi: Tracking log milik satu container.
     */
    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }
}
