<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    protected $fillable = ['user_id', 'tweet_id', 'reply', 'image'];

    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweets::class, 'tweet_id');
    }
}
