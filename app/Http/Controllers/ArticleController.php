<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Traits\ApiTraits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    use ApiTraits;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $articles = Article::with(['categories', 'author:id,name'])
            ->select('id', 'title', 'slug', 'content', 'author_id', 'published_at')
            ->get();
        $data = ArticleResource::collection($articles);

        return $this->apiResponse(1, 'Successfully retrieved articles', $data);
    }

    public function store(ArticleCreateRequest $request)
    {
        try {

            $article = Article::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'content' => $request->content,
                'author_id' => Auth::user()->id,
                'published_at' => now(),
            ]);
            $article->categories()->attach($request['categories']);

            $data = new ArticleResource($article);

            return $this->apiResponse(1, 'Successfully created', $data);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function update(ArticleUpdateRequest $request)
    {
        try {
            $article = Article::findOrFail($request->id);

            $article->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'content' => $request->content,
                'author_id' => Auth::user()->id,
                'published_at' => now(),
            ]);

            $article->categories()->sync($request['categories']);

            $data = new ArticleResource($article);

            return $this->apiResponse(1, 'Successfully Updated Article', $data);

        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $article = Article::findOrFail($request->id);
            $article->categories()->detach();
            $article->delete();

            return $this->apiResponse(1, 'Article deleted successfully');
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }

    //get all articles from category
    public function getCategoryArticle(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);

            $data = ArticleResource::collection($category->articles);

            return $this->apiResponse(1, 'Article retrieved successfully', $data);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    //get all articles from slug
    public function getSlugArticle(Request $request)
    {

        try {
            $article = Article::where('slug', $request->slug)->firstOrFail();

            $data = new ArticleResource($article);

            return $this->apiResponse(1, 'Article retrieved successfully', $data);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    //get auth user Articles
    public function getUserArticle()
    {
        try {
            $articles = Article::where('author_id', auth()->user()->id)->get();

            $data = ArticleResource::collection($articles);

            return $this->apiResponse(1, 'Article retrieved successfully', $data);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }
}
