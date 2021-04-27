<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model {
    use HasFactory;

    protected $table = 'member';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'joined' => 'datetime'
    ];

    public function createdAuctions() {
        return $this->hasMany( "App\Models\Auction", "seller_id");
    }

    public function bookmarkedAuctions() {
        return $this->hasMany("App\Models\BookmarkedAuction");
    }

    public function followers() {
        return $this->hasManyThrough(
            Member::class,
            Follow::class,
            "followed_id",
            "id",
            "id",
            "follower_id",
        );
    }

    public function notifications() {

    }
}
