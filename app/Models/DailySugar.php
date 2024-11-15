<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class DailySugar extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    
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
}
