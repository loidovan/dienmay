<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = DB::select('select @n := @n + 1 stt , c.id, c.name, DATE_FORMAT(c.created_at, "%d/%m/%Y %H:%i:%s") as created_at,
        DATE_FORMAT(c.updated_at, "%d/%m/%Y %H:%i:%s") as updated_at
        from categories as c, (SELECT @n := 0) as stt 
        order by c.id desc');
        return response()->json($category);
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
        if (auth()->user()->can('create-category')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ],
            [
                'name.required' => 'Tên không được để trống',
                'name.max' => 'Tên không được quá 255 ký tự',
                'name.unique' => 'Tên đã tồn tại',
            ]);
            $category = new Category();
            $category->name = $request->name;
            $category->created_at = date('Y-m-d H:i:s');
            $category->save();
            return response()->json([
                'message' => 'Thêm thành công',
            ], 200);
        }
        return response()->json([
            'message' => 'Bạn không có quyền thêm',
        ], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        return response()->json($category);
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
        if(auth()->user()->can('edit-category')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories'
            ],
            [
                'name.required' => 'Tên không được để trống',
                'name.max' => 'Tên không được quá 255 ký tự',
                'name.unique' => 'Tên đã tồn tại',
            ]);
            $category = Category::find($id);
            $category->name = $request->name;
            $category->updated_at = date('Y-m-d H:i:s');
            $category->save();
            return response()->json([
                'message' => 'Cập nhật thành công',
            ], 200);
        }
        return response()->json([
            'message' => 'Bạn không có quyền cập nhật',
        ], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        if(auth()->user()->can('delete-category')) {
            $ids = explode(',', $ids);
            $category = Category::whereIn('id', $ids)->delete();
            return response()->json([
                'message' => 'Xóa thành công',
            ], 200);
        }
        return response()->json([
            'message' => 'Bạn không có quyền xóa',
        ], 401);
    }
}
