<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Tweet;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function getFilter(Request $request)
    {
        $tags = Tag::where('name', $request->name)->get()->load(['tagComments', 'tagComments.comment','tagTweets','tagTweets.tweet', 'tagTweets.tweet.user', 'tagComments.comment.user'])->first();

        return response([
            "message" => "Filter success",
            "data" => $tags
        ]);
    }
}
