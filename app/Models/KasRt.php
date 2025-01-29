<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasRt extends Model
{
    use HasFactory;

    protected $table = 'kas_rts';

    protected $fillable = ['rt_id', 'pembayaran_id', 'jumlah_kas_rt'];

    public function rt()
    {
        return $this->belongsTo(RT::class, 'rt_id');
    }
    
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

}
