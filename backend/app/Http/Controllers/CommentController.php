<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tag;
use App\Models\TagComment;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            "comment" => "required",
            "tweet_id" => "required",
        ]);

        if ($validator->fails()) {
            return response([
                "message" => "Please input the commment"
            ], 422);
        }

        $tweet  = Tweet::find($request->tweet_id);
        if ($tweet == null) {
            return response([
                "message" => "The tweet doesn't exist"
            ]);
        }

        $comment = $tweet->comments()->create([
            "user_id" => $request->user()->id,
            "comment" => $request->comment
        ]);

        /**
         * Regex
         * 
         * Getting tag's and inserting it into specified database
         * 
         * Input: "Comment #tag#helloa"
         * Output: ["#tag", "helloa"];
         */

        preg_match_all('~(#[a-zA-Z0-9]+)~', $request->comment, $matches, PREG_PATTERN_ORDER);
        $tags = $matches[1];
        if ($tags != null) {
            foreach ($tags as $tag) {
                $tag = Tag::create([
                    "name" => $tag,
                ]);
                TagComment::create([
                    "tag_id" => $tag->id,
                    "comment_id" => $comment->id
                ]);
            }
        }

        return response([
            "message" => "Commenting tweet success"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $comment = Comment::find($id);
        if ($comment == null) {
            return response([
                "message" => "The comment isn't exist",
            ], 422);
        }
        return response([
            "message" => "Getting comment success",
            "data" => $comment->load(['user', 'tagComments'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): Response
    {
        $validator = Validator::make($request->all(), [
            "comment" => "required"
        ]);
        if ($validator->fails()) {
            return response([
                "message" => "Please input the commment"
            ], 422);
        }
        $comment = Comment::find($id);
        TagComment::where(['comment_id' => $id])->delete();

        $comment->update([
            "comment" => $request->comment,
        ]);


        /**
         * Regex
         * 
         * Getting tag's and inserting it into specified database
         * 
         * Input: "Comment #tag#helloa"
         * Output: ["#tag", "helloa"];
         */
        preg_match_all('~(#[a-zA-Z0-9]+)~', $request->comment, $matches, PREG_PATTERN_ORDER);
        $tags = $matches[1];
        if ($tags != null) {
            foreach ($tags as $tag) {
                $tag = Tag::updateOrCreate([
                    "name" => $tag,
                ]);
                $comment->tagComments()->create([
                    "tag_id" => $tag->id,
                ]);
            }
        }


        return response([
            "message" => "Updating comment success"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        Comment::find($id)->delete();
        return response([
            "message" => "Deleteing comment success"
        ]);
    }
}
