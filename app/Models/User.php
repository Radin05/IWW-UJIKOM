<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_kk_keluarga',
        'name',
        'email',
        'password',
        'role',
        'rt_id',
        'foto',
        'kedudukan',
        'login_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'login_time' => 'datetime',
    ];

    public function rt()
    {
        return $this->belongsTo(RT::class, 'rt_id', 'id');
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'no_kk_keluarga', 'no_kk');
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }

}
