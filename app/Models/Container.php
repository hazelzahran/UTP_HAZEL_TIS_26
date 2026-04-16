<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'containers';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'container_id',
        'waste_type',
        'weight_kg',
        'status'
    ];
}