<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangTambahan extends Model
{
    protected $fillable = ['nominal', 'keterangan'];

    /**
     * Relasi ke KasRw (opsional, jika ingin mengakses data kas dari transaksi ini)
     */
    public function kasRw()
    {
        return $this->hasOne(KasRw::class, 'uang_tambahan_kas_id');
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }
}
