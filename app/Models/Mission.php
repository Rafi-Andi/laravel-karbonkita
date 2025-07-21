<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mission extends Model
{
    /** @use HasFactory<\Database\Factories\MissionFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function activity():HasMany{
        return $this->hasMany(Activity::class);
    }
    
    public function category():BelongsTo{
        return $this->belongsTo(Category::class)->withTrashed();
    }
}