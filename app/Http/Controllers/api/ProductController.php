<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;

use App\Repositories\Product\ProductRepositories;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    protected $product_repositories;
    protected $product_service;

    /**
     * ProductController constructor.
     *
     * @param ProductRepositories $product_repositories
     * @param ProductService $product_service
     */

    public function __construct(ProductRepositories $product_repositories , ProductService $product_service)
    {
        $this->product_repositories = $product_repositories;
        $this->product_service = $product_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->category)
        {
            $product_cate = $this->product_repositories->getProductByCategory($request);
            return \App\Http\Resources\Product::collection($product_cate);
        }else
        {
            $product = $this->product_repositories->getAllProduct($request);
            return \App\Http\Resources\Product::collection($product);
        }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $thumbnail   = uploadImg($request, public_path() . '/uploads/');
        $product = $this->product_service->createProduct($request , $thumbnail);
        return $product ? $this->responseSuccess(null,
            'Bạn đã thêm sản phẩm thành công') : $this->responseError(null, 'Bạn đã thêm sản phẩm thất bại',
            self::STATUS_ERROR_WITH_MESSAGE);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

         $product_detail = $this->product_repositories->getItemProduct($id);
         if($product_detail->price_sale)
         {
             $accumulation = $product_detail->price - $product_detail->price_sale;
             $product_detail['accumulation'] = $accumulation;

         }
         return new \App\Http\Resources\Product($product_detail);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
            $thumbnail   = uploadImg($request, public_path() . '/uploads/');
            $isUpdate = $this->product_service->updateProduct($request , $thumbnail , $id);
            return $isUpdate ? $this->responseSuccess(null,
                'Bạn đã cập nhật sản phẩm thành công') : $this->responseError(null, 'Bạn đã cập nhật sản phẩm thất bại',
                self::STATUS_ERROR_WITH_MESSAGE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $isDelete = Product::find($id)->delete();
        return $isDelete ? $this->responseSuccess(null,
            'Bạn đã xóa sản phẩm thành công') : $this->responseError(null, 'Bạn đã xóa sản phẩm thất bại',
            self::STATUS_ERROR_WITH_MESSAGE);
    }
}
