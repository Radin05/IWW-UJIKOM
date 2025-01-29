<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluargas';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['no_kk', 'nama_keluarga', 'alamat', 'no_telp', 'rt_id'];

    public function rts()
    {
        return $this->belongsTo(RT::class, 'rt_id');
    }

    public function no_kk()
    {
        return $this->hasOne(User::class);
    }

    public function bayar()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'no_kk');
    }

}
