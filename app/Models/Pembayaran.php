<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = ['no_kk_keluarga', 'sejumlah', 'year', 'month', 'tgl_pembayaran'];

    protected $casts = [
        'tgl_pembayaran' => 'datetime',
    ];

    public function no_kk()
    {
        return $this->belongsTo(Keluarga::class, 'no_kk_keluarga');
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }
    
}
