<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasRw extends Model
{
    use HasFactory;

    protected $table = 'kas_rws';

    protected $fillable = ['pembayaran_id', 'jumlah_kas_rw', 'pengeluaran_kas_rw_id', 'uang_tambahan_kas_id'];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }
    
}
