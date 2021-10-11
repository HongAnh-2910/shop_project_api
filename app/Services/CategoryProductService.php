<?php

namespace App\Services;

use App\Category;
use App\Repositories\CategoryProduct\CategoryProductRepositories;

class CategoryProductService
{
    protected $category_product_repositories;
    protected $category;

    /**
     * CategoryProductService constructor.
     *
     * @param CategoryProductRepositories $category_product_repositories
     * @param Category $category
     */
    public function __construct(CategoryProductRepositories $category_product_repositories, Category $category)
    {
        $this->category_product_repositories = $category_product_repositories;
        $this->category                      = $category;
    }


    /**
     * @param $thumbnail
     * @param $id
     * @param $cate_item
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateCategory($thumbnail, $id, $cate_item, $request)
    {
        if ( ! empty($thumbnail)) {
            $update_cate = $this->category::where('id', $id)->update([
                'name_category' => $request->input('name_category'),
                'img'           => url('public/uploads/' . $thumbnail),
                'parent_id'     => $request->input('parent_id')
            ]);
            if ($update_cate) {
                return true;
            } else {
                return false;
            }
        } else {
            $update_cate = $this->category::where('id', $id)->update([
                'name_category' => $request->input('name_category'),
                'img'           => $cate_item->img,
                'parent_id'     => $request->input('parent_id')
            ]);
            if ($update_cate) {
                return true;
            } else {
                return false;
            }
        }
    }

}
