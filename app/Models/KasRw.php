<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasRw extends Model
{
    use HasFactory;

    protected $table = 'kas_rw_s';

    protected $fillable = ['pembayaran_id', 'jumlah_kas_rw'];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
