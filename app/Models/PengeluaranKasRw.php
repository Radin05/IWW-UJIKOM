<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranKasRw extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_kas_rws';

    protected $fillable = [
        'nominal',
        'kegiatan_id',
        'keterangan',
        'tgl_pengeluaran',
    ];

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id')
            ->where('target_table', 'pengeluaran_kas_rws');
    }

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanRw::class, 'kegiatan_id');
    }

}
