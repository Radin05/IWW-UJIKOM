<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangTambahanRT extends Model
{
    use HasFactory;
    
    protected $table = 'uang_tambahan_rts';
    protected $fillable = ['nominal', 'keterangan', 'rt_id'];

    public function kasRt()
    {
        return $this->hasOne(KasRt::class, 'uang_tambahan_kas_id');
    }
}
