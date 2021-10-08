<?php

namespace App\Http\Controllers\api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateCategoryRequest;
use App\Http\Requests\ValidateUpadteCategoryRequest;
use Illuminate\Http\Request;

class CategorysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = '';
        if($request->input('search'))
        {
            $search =  $request->input('search');
        }
        $list_category =  Category::with('category_child')
                                  ->where('name_category', 'LIKE' , "%{$search}%")
                                  ->paginate();
        return response()->json($list_category , 201);
        //
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
        $thumbnail =  uploadImg($request , 'public/uploads');
        Category::create([
            'name_category' => $request->input('name_category'),
            'img' => url('public/uploads/'.$thumbnail),
            'parent_id' => $request->input('parent_id')
        ]);

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
        return response()->json(Category::find($id) , 201);
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
    public function update(ValidateUpadteCategoryRequest $request, $id)
    {
        $cate_item = Category::find(5);
        $thumbnail =  uploadImg($request , 'public/uploads');
        if(!empty($thumbnail))
        {
           $update_cate = Category::where('id' , $id)->update([
                'name_category' => $request->input('name_category'),
                'img' => url('public/uploads/'.$thumbnail),
                'parent_id' => $request->input('parent_id')
            ]);
           if($update_cate)
           {
               return response()->json(['message' => 'Bạn đã cập nhật danh mục thành công'], 201);
           }else
           {
               return response()->json(['message' => 'Bạn đã cập nhật danh mục thất bại'], 422);
           }
        }else
        {
            $update_cate = Category::where('id' , $id)->update([
                'name_category' => $request->input('name_category'),
                'img' => $cate_item->img,
                'parent_id' => $request->input('parent_id')
            ]);
            if($update_cate)
            {
                return response()->json(['message' => 'Bạn đã cập nhật danh mục thành công'], 201);
            }else
            {
                return response()->json(['message' => 'Bạn đã cập nhật danh mục thất bại'], 422);
            }
        }
    }

    /**
     * @param Request $request
     * @param $id
     */

    public function delete(Request $request , $id)
    {
       $delete_item =  Category::find($id)->delete();
       if($delete_item)
       {
           return response()->json(['message' => 'Bạn đã xóa danh mục thành công'], 201);
       }else
       {
           return response()->json(['error' => 'Bạn đã xóa danh mục thất bại'], 422);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy_item = Category::withTrashed()->where('id' , $id)->forceDelete();
        if($destroy_item)
        {
            return response()->json(['message' => 'Bạn đã xóa vĩnh viễn danh mục thành công'], 201);
        }else
        {
            return response()->json(['error' => 'Bạn đã xóa vĩnh viễn danh mục thất bại'], 422);
        }
    }
}
