<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    // Ini wajib agar terhindar dari error Mass Assignment
    protected $fillable = ['container_id', 'waste_type', 'weight_kg', 'status'];
}