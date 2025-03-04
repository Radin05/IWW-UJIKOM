<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalasKomentar extends Model
{
    use HasFactory;

    protected $fillable = ['komentar_id', 'user_id', 'konten', 'performed_at'];

    public function komentar()
    {
        return $this->belongsTo(Komentar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }
}
