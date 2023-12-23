<?php

namespace App\Repositories;

use App\Contracts\BaseInterface;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryRepository implements BaseInterface
{
    public function index()
    {
        $categories = Category::select('id', 'name')->get();

        return CategoryResource::collection($categories);

    }

    public function store($request)
    {
        return Category::create([
            'name' => $request->name,
        ]);

    }

    public function show($request)
    {
        return Category::findorFail($request->id);
    }

    public function update($request)
    {
        $category = Category::findOrFail($request->id);

        return $category->update([
            'name' => $request->name,
        ]);

    }

    public function delete($request)
    {
        $category = Category::findOrFail($request->id);
        $category->articles()->detach();
        $category->delete();

    }
}
