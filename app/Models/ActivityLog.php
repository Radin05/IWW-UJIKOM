<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'target_table',
        'target_id',
        'performed_at',
    ];

    public function rt()
    {
        return $this->belongsTo(RT::class, 'target_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
