<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tag;
use App\Models\TagTweet;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $tweets = Tweet::all()->sortByDesc('created_at')->load(['user', 'tagTweets', 'comments', 'comments.tagComments.tag', 'comments.user'])->values();
        return response([
            "message" => "Successfully getting data",
            "data" => $tweets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            "tweet" => "required|max:250",
        ]);
        if ($validator->fails()) {
            return response([
                "message" => $validator->messages()
            ], 422);
        }

        $credentials = [
            "user_id" => $request->user()->id,
            "tweet" => $request->tweet
        ];
        if ($request->file('media') !== null) {
            $file = $request->file('media');
            $filename = uniqid() . "." . $file->getClientOriginalExtension();
            $path = $file->storeAs("public/uploads", $filename);

            $credentials["media"] = Storage::url($path);
        }

        preg_match_all('~(#[a-zA-Z0-9]+)~', $request->tweet, $matches, PREG_PATTERN_ORDER);
        $tags = $matches[1];
        $tweet = Tweet::create($credentials);
        if ($tags != null) {
            foreach ($tags as $tag) {
                $tag = Tag::updateOrCreate([
                    "name" => $tag,
                ], ["name" => "$tag"]);
                TagTweet::create([
                    "tweet_id" => $tweet->id,
                    "tag_id" => $tag->id
                ]);
            }
        }
        return response([
            "message" => "Create tweet success",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $tweet  = Tweet::find($id);
        if ($tweet == null) {
            return response([
                "message" => "The tweet doesn't exist"
            ], 422);
        }
        return response([
            "message" => "Getting Tweet and comments success",
            "data" => $tweet->load(['user', 'tagTweets', 'comments', 'comments.tagComments.tag', 'comments.user'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): Response
    {
        $validator = Validator::make($request->all(), [
            "tweet" => "required|max:250",
            "update_status" => "required"
        ]);
        if ($validator->fails()) {
            return response([
                "message" => $validator->messages()
            ], 422);
        }
        $credentials = [
            "user_id" => $request->user()->id,
            "tweet" => $request->tweet,
        ];

        if ($request->update_status == 'true') {
            if ($request->file('media') !== null) {
                $file = $request->file('media');
                $filename = uniqid() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs("public/uploads", $filename);

                $credentials["media"] = Storage::url($path);
            } else {
                $credentials["media"] = "";
            }
        }

        $tweet = Tweet::find($id);
        TagTweet::where(['tweet_id'=>$id])->delete();

        if (file_exists($tweet->media)) @unlink($tweet->media);
        $tweet->update($credentials);

        preg_match_all('~(#[a-zA-Z0-9]+)~', $request->tweet, $matches, PREG_PATTERN_ORDER);
        $tags = $matches[1];
        if ($tags != null) {
            foreach ($tags as $tag) {
                $tag = Tag::updateOrCreate([
                    "name" => $tag,
                ], ["name" => "$tag"]);
                $tweet->tagTweets()->create([
                    "tag_id" => $tag->id
                ]);
            }
        }
        return response([
            "message" => "Successfully update tweet",
            "data" => $tweet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        Tweet::find($id)->delete();
        return response([
            "message" => "Successfully delete tweet",
        ]);
    }

    public function user(Request $request): Response
    {
        return response([
            "data" => [
                "tweets" => Tweet::where(['user_id' => $request->user()->id])->get()->sortByDesc('created_at')->load(['user', 'tagTweets', 'comments', 'comments.tagComments.tag', 'comments.user'])->values(),
                "comments" => Comment::where(['user_id' => $request->user()->id])->get()->sortByDesc('created_at')->load(['user', 'tagComments', 'tweet'])->values()
            ]
        ]);
    }
}
