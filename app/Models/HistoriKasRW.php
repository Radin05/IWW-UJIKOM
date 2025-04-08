<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriKasRW extends Model
{
    use HasFactory;

    protected $table = 'histori_kas_rw';

    protected $fillable = [
        'nominal',
        'tgl_pembaruan_kas',
    ];
}
