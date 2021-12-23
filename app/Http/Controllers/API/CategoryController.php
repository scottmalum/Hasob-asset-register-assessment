<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCategoryRequest;
use App\Http\Requests\API\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends AppBaseController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = $this->categoryRepository->all();
        return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $data = $request->all();
        //Create Category in the repository
        $category = $this->categoryRepository->create($data);

        //Return the JSON version of the created Category.
        return $this->sendResponse($category->toArray(), 'Category saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->sendError('Category not found');
        }

        return $this->sendResponse($category->toArray(), 'Category Retrieved Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->all();
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->sendError('Category not found');
        }

        $category = $this->categoryRepository->update($data, $id);

        return $this->sendResponse($category->toArray(), 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->sendError('Category not found');
        }

        $category->delete();

        return $this->sendSuccess('Category Deleted Successfully');
    }
}
