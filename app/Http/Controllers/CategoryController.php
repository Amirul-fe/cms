<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use App\Traits\ApiTraits;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiTraits;

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->middleware('auth:api');
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {

        $data = $this->categoryRepository->index();

        return $this->apiResponse(1, 'Successfully retrieved category', $data);
    }

    public function store(CategoryRequest $request)
    {

        try {
            $category = $this->categoryRepository->store($request);

            return $this->apiResponse(1, 'Successfully created category', $category);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {
            $category = $this->categoryRepository->show($request);

            return $this->apiResponse(1, 'Successfully data retrieve', $category);
        } catch (Exception $e) {
            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function update(CategoryUpdateRequest $request)
    {

        try {
            $category = $this->categoryRepository->update($request);

            return $this->apiResponse(1, 'Successfully Category updated');
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }

    }

    public function delete(Request $request)
    {
        try {
            $this->categoryRepository->delete($request);

            return $this->apiResponse(1, 'Category deleted successfull');
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());
        }
    }
}
