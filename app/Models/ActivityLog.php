<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasFactory;

    //table 
    protected $table = 'activity_logs';

    //guarded
    protected $guarded = ['id'];

    //connect into user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
