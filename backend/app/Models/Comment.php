<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function tweet()
    {
        return $this->belongsTo(Tweet::class)->orderBy('created_at');
    }
    public function tagComments()
    {
        return $this->hasMany(TagComment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
