<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'konten', 'performed_at', 'kegiatan_rt_id', 'kegiatan_rw_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function balasKomentar()
    {
        return $this->hasMany(BalasKomentar::class);
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanRw::class);
    }
}
