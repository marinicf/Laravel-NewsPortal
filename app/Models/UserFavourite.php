<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use App\Models\User;

class UserFavourite extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'title',
        'url',
        'author',
        'description',
        'image_url',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
