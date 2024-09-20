<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Inbox extends Model
{
    use HasFactory;

    protected $fillable = ['creator_id', 'inboxable_id', 'inboxable_type'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function inboxable(): MorphTo
    {
        return $this->morphTo();
    }

    public function latestMessage()
    {
        return $this->hasMany(Message::class)->latest()->first();
    }

}
