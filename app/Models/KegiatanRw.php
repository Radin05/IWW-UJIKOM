<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanRw extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_rws';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal_kegiatan',
        'jam_kegiatan',
        'status',
    ];

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }
}
