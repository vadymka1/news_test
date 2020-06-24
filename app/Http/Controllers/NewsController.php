<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\NewsCreateRequest;
use App\News;
use App\Services\JWTService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $news = News::all();

        return new JsonResponse(['data' => $news]);
    }

    /**
     * @param News $news
     * @return JsonResponse
     */
    public function show(News $news)
    {
        return new JsonResponse(['data' => $news]);
    }

    /**
     * @param NewsCreateRequest $request
     * @param News $news
     * @return JsonResponse
     */
    public function store(NewsCreateRequest $request, News $news)
    {
        $user = auth()->guard('api')->user();

        $data = $request->all();
        $data['user_id'] = $user->id;

        $news->forceFill($data);
        $news->save();

        return new JsonResponse([$news]);
    }

    /**
     * @param Request $request
     * @param News $news
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, News $news) : JsonResponse
    {
        $user = auth()->guard('api')->user();
        if ($user->id == $news->user->id ) {
            $news->delete();

            return new JsonResponse(null, 204);
        }

        return new JsonResponse(['error' => 'Some error'], 200);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function showByUser(User $user)
    {
        return new JsonResponse(['data' => $user->news]);
    }

    /**
     * @return JsonResponse
     */
    public function mostCommentable()
    {
        $newsIds = Comment::query()->where('commentable_type', News::class)
            ->orderBy('commentable_id','desc')
            ->groupBy('commentable_id')
            ->pluck('commentable_id')
            ->toArray();

        $news = News::query()->whereIn('id', $newsIds)->limit(5)->get();

        return new JsonResponse(['data' => $news]);
    }
}
