<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use App\Models\User;

class UserComment extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'user_id',
        'url',
        'comment_text',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
