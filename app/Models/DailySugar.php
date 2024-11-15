<?php

namespace App\Models;

use App\Filters\DailySugarFilter;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class DailySugar extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Filterable;
    
    protected $fillable = [
        'user_id',
        'mgdl',
        'description',
        'date',
    ];

    protected $hidden = [
        "updated_at", 
        "deleted_at"
    ];

    protected string $default_filters = DailySugarFilter::class;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
