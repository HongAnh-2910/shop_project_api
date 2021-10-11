<?php

namespace App\Repositories\CategoryProduct;
use App\Category;

class CategoryProductRepositories implements CategoryProductRepositoryInterface
{
    protected $category;

    /**
     * CategoryProductRepositories constructor.
     *
     * @param Category $category
     */

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function getAllCategory($request)
    {
        $search = '';
        if($request->input('search'))
        {
            $search =  $request->input('search');
        }
       return $this->category::with('category_child')
                                  ->where('name_category', 'LIKE' , "%{$search}%")
                                  ->paginate();
    }

    /**
     * @param $thumbnail
     * @param $request
     *
     * @return mixed
     */

    public function create($thumbnail , $request)
    {
        return $this->category::create([
            'name_category' => $request->input('name_category'),
            'img' => url('uploads/'.$thumbnail),
            'parent_id' => $request->input('parent_id')
        ]);
    }

    /**
     * @param $id
     *
     * @return mixed
     */

    public function delete($id)
    {
        return $this->category::find($id)->delete();
    }

    /**
     * @param $id
     *
     * @return bool|mixed|null
     */

    public function destroy($id)
    {
        return $this->category::withTrashed()->where('id', $id)->forceDelete();
    }

}
