<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriKasRT extends Model
{
    use HasFactory;

    protected $table = 'histori_kas_rt';

    protected $fillable = [
        'rt_id',
        'nominal',
        'tgl_pembaruan_kas',
    ];

    public function rt()
    {
        return $this->belongsTo(RT::class, 'rt_id');
    }
}
