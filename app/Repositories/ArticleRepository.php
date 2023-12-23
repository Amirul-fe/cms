<?php

namespace App\Repositories;

use App\Contracts\BaseInterface;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleRepository implements BaseInterface
{
    public function index()
    {
        $articles = Article::with(['categories', 'author:id,name'])
            ->select('id', 'title', 'slug', 'content', 'author_id', 'published_at')
            ->get();

        return ArticleResource::collection($articles);

    }

    public function store($request)
    {

        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'content' => $request->content,
            'author_id' => Auth::user()->id,
            'published_at' => now(),
        ]);
        $article->categories()->attach($request['categories']);

        return new ArticleResource($article);

    }

    public function show($request)
    {
        return Article::findorFail($request->id);
    }

    public function update($request)
    {
        $article = Article::findOrFail($request->id);

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'content' => $request->content,
            'author_id' => Auth::user()->id,
            'published_at' => now(),
        ]);

        $article->categories()->sync($request['categories']);

        return new ArticleResource($article);
    }

    public function delete($request)
    {
        $article = Article::findOrFail($request->id);
        $article->categories()->detach();
        $article->delete();
    }
}
