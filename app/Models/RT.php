<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RT extends Model
{
    use HasFactory;

    protected $table = 'rts';

    protected $fillable = ['nama_RT'];

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'rt_id');
    }

    public function rt()
    {
        return $this->hasMany(User::class);
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class, 'target_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            ActivityLog::where('target_id', $model->id)
                ->where('target_table', 'rts')
                ->update(['target_id' => null]);
        });
    }
}
