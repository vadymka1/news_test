<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreateCommentRequest;
use App\News;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @param CreateCommentRequest $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCommentRequest $request, Comment $comment)
    {
        $user = auth()->guard('api')->user();

        $comment->body = $request->get('body');
        $comment->user()->associate($user);
        $news = News::find($request->get('news_id'));
        $news->comments()->save($comment);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @param CreateCommentRequest $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function replyStore(CreateCommentRequest $request, Comment $comment)
    {
        $user = auth()->guard('api')->user();

        $comment->body = $request->get('body');
        $comment->user()->associate($user);
        $comment->parent_id = $request->get('comment_id');
        $news = News::find($request->get('news_id'));

        $news->comments()->save($comment);

        return response()->json([
            'success' => true,
        ]);
    }
}
