<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiTraits;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiTraits;

    public function index()
    {
        $categories = Category::select('id', 'name')->get();

        $data = CategoryResource::collection($categories);

        return $this->apiResponse(1, 'Successfully retrieved category', $data);
    }

    public function store(CategoryRequest $request)
    {

        try {
            $category = Category::create([
                'name' => $request->name,
            ]);

            return $this->apiResponse(1, 'Successfully created category', $category);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function edit(Request $request)
    {
        $category = Category::findorFail($request->id);

        return $this->apiResponse(1, 'Successfully data retrieve', $category);
    }

    public function update(CategoryUpdateRequest $request)
    {

        try {
            $category = Category::findOrFail($request->id);

            $category->update([
                'name' => $request->name,
            ]);

            return $this->apiResponse(1, 'Successfully Category updated', $category);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function delete(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            $category->articles()->detach();
            $category->delete();

            return $this->apiResponse(1, 'Category deleted successfull');
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }
}
