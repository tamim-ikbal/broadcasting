<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Relations\BidirectionalRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Relations\Relations as CustomRelations;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CustomRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return $attributes['avatar'] ? asset('storage/'.$attributes['avatar']) : 'https://placehol.co/100x100';
            },
        );
    }

    //Works
//    public function getFriends()
//    {
//        return User::query()
//                   ->join('friends', function (JoinClause $clause) {
//                       $clause->on(function (JoinClause $subOn) {
//                           $subOn->on('users.id', '=', 'friends.friend_id')
//                                 ->where('friends.user_id', $this->id);
//                       })->orOn(function (JoinClause $subOrOn) {
//                           $subOrOn->on('users.id', '=', 'friends.user_id')
//                                   ->where('friends.friend_id', $this->id);
//                       });
//                   })
//                   ->get();
//    }

//    public function friends(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id');
//    }

    public function friends(): BidirectionalRelation
    {
        return $this->bidirectionalRelation(User::class, 'friends', 'user_id', 'friend_id')->withTimestamps();
    }

    public function ownInbox()
    {
        return $this->hasMany(Message::class, 'creator_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

}
