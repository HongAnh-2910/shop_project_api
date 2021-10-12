<?php

namespace App\Http\Controllers\api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateCategoryRequest;
use App\Http\Requests\ValidateUpadteCategoryRequest;
use App\Http\Resources\CategoryProduct;
use App\Repositories\CategoryProduct\CategoryProductRepositories;
use App\Services\CategoryProductService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $category_product_repositories;
    protected $category_product_service;

    /**
     * CategoryController constructor.
     *
     * @param CategoryProductRepositories $category_product_repositories
     * @param CategoryProductService $category_product_service
     */

    public function __construct(
        CategoryProductRepositories $category_product_repositories,
        CategoryProductService $category_product_service
    ) {
        $this->category_product_repositories = $category_product_repositories;
        $this->category_product_service      = $category_product_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $list_category = $this->category_product_repositories->getAllCategory($request);

        return CategoryProduct::collection($list_category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param ValidateCategoryRequest $request
     */

    public function store(ValidateCategoryRequest $request)
    {

        $thumbnail               = uploadImg($request, public_path() . '/uploads/');
        $create_category_product = $this->category_product_repositories->create($thumbnail, $request);

        return $create_category_product ? $this->responseSuccess(null,
            'Bạn đã thêm danh mục thành công') : $this->responseError(null, 'Bạn đã thêm danh mục thất bại',
            self::STATUS_ERROR_WITH_MESSAGE);

        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        return new CategoryProduct(Category::find($id));
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * @param ValidateUpadteCategoryRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(ValidateUpadteCategoryRequest $request, $id)
    {
        $cate_item = Category::find($id);
        $thumbnail = uploadImg($request, 'public/uploads');
        $isUpdated = $this->category_product_service->updateCategory($thumbnail, $id, $cate_item, $request);

        return $isUpdated ? $this->responseSuccess(null, 'Bạn đã cập nhật danh mục thành công') : $this->responseError(null,
            'Bạn cập nhật danh mục thất bại', self::STATUS_ERROR_WITH_MESSAGE);
    }

    /**
     * @param Request $request
     * @param $id
     */

    public function delete($id)
    {
        $delete_item = $this->category_product_repositories->delete($id);

        return $delete_item ? $this->responseSuccess(null, 'Bạn đã xóa danh mục thành công') : $this->responseError(null,
            'Bạn đã xóa danh mục thất bại', self::STATUS_ERROR_WITH_MESSAGE);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy_item = $this->category_product_repositories->destroy($id);
        return $destroy_item ?$this->responseSuccess(null,
            'Bạn đã xóa vĩnh viễn thành công') : $this->responseError(null, 'Bạn đã xóa vĩnh viễn thất bại',
            self::STATUS_ERROR_WITH_MESSAGE);
    }
}
