<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranKasRt extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_kas_rts';

    protected $fillable = [
        'nominal',
        'kegiatan_id',
        'keterangan',
        'tgl_pengeluaran',
        'rt_id',
    ];

    public function rt()
    {
        return $this->belongsTo(RT::class);
    }

    public function kas()
    {
        return $this->hasMany(KasRt::class, 'pengeluaran_kas_rt_id');
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanRt::class, 'kegiatan_id');
    }

}
