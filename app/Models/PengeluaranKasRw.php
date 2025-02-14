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
        'keterangan',
        'tgl_pengeluaran',
        'year',
    ];

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }

}
