<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'date_start',
        'date_end',
    ];

    public function getPengajuans(): HasMany {
        return $this->hasMany(Pengajuan::class, 'periode_id', 'id');
    }
     public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
}
