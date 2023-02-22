<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTweet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
