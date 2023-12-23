<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Repositories\ArticleRepository;
use App\Traits\ApiTraits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use ApiTraits;

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->middleware('auth:api');
        $this->articleRepository = $articleRepository;
    }

    public function index()
    {
        $data = $this->articleRepository->index();

        return $this->apiResponse(1, 'Successfully retrieved articles', $data);
    }

    public function store(ArticleCreateRequest $request)
    {
        try {

            $data = $this->articleRepository->store($request);

            return $this->apiResponse(1, 'Successfully created', $data);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function show(Request $request)
    {
        try {
            $article = $this->articleRepository->show($request);

            return $this->apiResponse(1, 'Successfully Article retrieve', $article);
        } catch (Exception $e) {
            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function update(ArticleUpdateRequest $request)
    {
        try {
            $data = $this->articleRepository->update($request);

            return $this->apiResponse(1, 'Successfully Updated Article', $data);

        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->articleRepository->delete($request);

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

    //get articles slug wise
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
            $articles = Article::with(['categories', 'author:id,name'])
                ->where('author_id', auth()->user()->id)
                ->get();

            $data = ArticleResource::collection($articles);

            return $this->apiResponse(1, 'Article retrieved successfully', $data);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }
}
