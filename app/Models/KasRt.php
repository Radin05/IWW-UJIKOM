<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasRt extends Model
{
    use HasFactory;

    protected $table = 'kas_rts';

    protected $fillable = ['rt_id', 'pembayaran_id', 'jumlah_kas_rt', 'pengeluaran_kas_rt_id', 'uang_tambahan_kas_id'];

    public function rt()
    {
        return $this->belongsTo(RT::class, 'rt_id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function pengeluaran()
    {
        return $this->belongsTo(PengeluaranKasRt::class, 'pengeluaran_kas_rt_id');
    }
    
    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }
}
