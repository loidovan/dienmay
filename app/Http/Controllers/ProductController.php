<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if (auth()->user()->can('create-product')) {
            $request->validate(
                [
                    'code' => 'required|unique:products|max:255',
                    'name' => 'required|unique:products|max:255',
                    'category_id' => 'required',
                    'brand_id' => 'required',
                    'type_id' => 'required',
                    'image' => 'required|mimes:jpg,jpeg,png,bmp|max:20000',
                    'description' => 'required',
                    'color_ids' => 'required',
                    'price' => 'required',
                    'warranty' => 'required',
                    'images' => 'required'
                ],
                [
                    'code.required' => 'Mã sản phẩm không được để trống',
                    'code.unique' => 'Mã sản phẩm đã tồn tại',
                    'code.max' => 'Mã sản phẩm không được quá 255 ký tự',
                    'name.required' => 'Tên màu không được để trống',
                    'name.unique' => 'Tên màu đã tồn tại',
                    'name.max' => 'Tên màu không được quá 255 ký tự',
                    'image.required' => 'Ảnh sản phẩm không được để trống',
                    'image.mimes' => 'File không đúng định dạng (định dạng yêu cầu là jpg, jpeg, png, bmp)',
                    'image.max' => 'Kích thước file vượt quá 20MB',
                    'category_id.required' => 'Danh mục sản phẩm không được để trống',
                    'brand_id.required' => 'Thương hiệu sản phẩm không được để trống',
                    'type_id.required' => 'Loại sản phẩm không được để trống',
                    'color_ids.required' => 'Màu sản phẩm không được để trống',
                    'description.required' => 'Mô tả sản phẩm không được để trống',
                    'price.required' => 'Giá sản phẩm không được để trống',
                    'warranty.required' => 'Bảo hành sản phẩm không được để trống',
                    'images.required' => 'Ảnh sản phẩm không được để trống'
                ]
            );

            $product = new Product();

            if ($request->file()) {
                $file_name = time() . '_' . $request->image->getClientOriginalName();
                $file_path = $request->file('image')->storeAs('images/products', $file_name, 'public');

                $product->code = $request->code;
                $product->name = $request->name;
                $product->category_id = $request->category_id;
                $product->brand_id = $request->brand_id;
                $product->type_id = $request->type_id;
                $product->description = $request->description;
                $product->image = '/storage/' . $file_path;
                $product->price = $request->price;
                $product->warranty = $request->warranty;
                $product->created_at = date('Y-m-d H:i:s');

                $product->save();

                $colors = Color::find(explode(',', $request->color_ids));
                $product->colors()->attach($colors);

                $images = explode(',', $request->images);

                foreach ($images as $image) {
                    $from = public_path('tmp/uploads/' . $image);
                    $to = storage_path('app/public/images/products/' . $image);

                    File::move($from, $to);
                    $product->images()->create([
                        'name' => $image,
                    ]);
                }
            
                File::cleanDirectory(public_path('/tmp/uploads'));

                return response()->json([
                    'message' => 'Thêm thành công',
                ], 200);
            }
        }
        return response()->json([
            'message' => 'Bạn không có quyền thêm sản phẩm'
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
        $product = Product::with('brand', 'category', 'type', 'colors', 'images')->find($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (auth()->user()->can('edit-product')) {
            $request->validate(
                [
                    'code' => 'required|max:255',
                    'name' => 'required|max:255',
                    'category_id' => 'required',
                    'brand_id' => 'required',
                    'type_id' => 'required',
                    'image' => 'required|mimes:jpg,jpeg,png,bmp|max:20000',
                    'description' => 'required',
                    'price' => 'required',
                    'warranty' => 'required',
                    'images' => 'required'
                ],
                [
                    'code.required' => 'Mã sản phẩm không được để trống',
                    'code.max' => 'Mã sản phẩm không được quá 255 ký tự',
                    'name.required' => 'Tên màu không được để trống',
                    'name.max' => 'Tên màu không được quá 255 ký tự',
                    'image.required' => 'Ảnh sản phẩm không được để trống',
                    'image.mimes' => 'File không đúng định dạng (định dạng yêu cầu là jpg, jpeg, png, bmp)',
                    'image.max' => 'Kích thước file vượt quá 20MB',
                    'category_id.required' => 'Danh mục sản phẩm không được để trống',
                    'brand_id.required' => 'Thương hiệu sản phẩm không được để trống',
                    'type_id.required' => 'Loại sản phẩm không được để trống',
                    'description.required' => 'Mô tả sản phẩm không được để trống',
                    'price.required' => 'Giá sản phẩm không được để trống',
                    'warranty.required' => 'Bảo hành sản phẩm không được để trống',
                    'images.required' => 'Ảnh sản phẩm không được để trống'
                ]);

            $product = Product::find($id);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
