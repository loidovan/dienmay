<?php

namespace App\Http\Controllers;

use App\Models\ImportProduct;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Http\Request;

class ImportProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $importProducts = ImportProduct::with('product')->orderBy('id', 'desc')->get();
        $i = 1;
        foreach ($importProducts as $importProduct) {
            $importProduct['stt'] = $i;
            $i++;
        }
        return response()->json($importProducts);
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
        $request->validate(
            [
                'code' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'price' =>  'required|integer',
                'supplier' => 'required|string|max:255',
                'supplier_address' => 'required|string|max:255',
                'supplier_phone' => 'required|string|between:10,11',
            ],
            [
                'code.required' => 'Mã sản phẩm không được để trống',
                'code.max' => 'Mã sản phẩm không được quá 255 ký tự',
                'quantity.required' => 'Số lượng nhập không được để trống',
                'quantity.integer' => 'Số lượng nhập phải là số',
                'price.required' => 'Đơn giá nhập không được để trống',
                'price.integer' => 'Đơn giá nhập phải là số',
                'supplier.required' => 'Nhà cung cấp không được để trống',
                'supplier.max' => 'Nhà cung cấp không được quá 255 ký tự',
                'supplier_address.required' => 'Địa chỉ nhà cung cấp không được để trống',
                'supplier_address.max' => 'Địa chỉ nhà cung cấp không được quá 255 ký tự',
                'supplier_phone.required' => 'SĐT nhà cung cấp không được để trống',
                'supplier_phone.between' => 'SĐT nhà cung cấp từ 10-11 ký tự',
            ]
        );

        $checkExistProduct = Product::where('code', $request->code)->first();
        if (!$checkExistProduct) {
            return response()->json([
                'message' => 'Thêm thất bại',
                'errors' => [
                    'code' => ['Mã sản phẩm không hợp lệ']
                ]
            ], 500);
        }

        $import = new ImportProduct();
        $import->product_id = $checkExistProduct->id;
        $import->quantity = $request->quantity;
        $import->price = $request->price;
        $import->supplier = $request->supplier;
        $import->supplier_address = $request->supplier_address;
        $import->supplier_phone = $request->supplier_phone;
        $import->created_at = date('Y-m-d H:i:s');
        $import->save();
        return response()->json([
            'message' => 'Thêm thành công',
        ], 200);

        return response()->json([
            'message' => 'Thêm thất bại',
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImportProduct  $importProduct
     * @return \Illuminate\Http\Response
     */
    public function show(ImportProduct $importProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImportProduct  $importProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(ImportProduct $importProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImportProduct  $importProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImportProduct $importProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $ids
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        $importProducts = ImportProduct::whereIn('id', $ids)->delete();
        return response()->json([
            'message' => 'Xóa thành công',
        ], 200);

        return response()->json([
            'message' => 'Xóa thất bại',
        ], 500);
    }

    public function getInventory()
    {
        $products = Product::all();

        $i = 1;
        foreach ($products as $product) {
            $product['stt'] = $i;
            $product['quantitySold'] = OrderDetails::where('product_id', $product->id)
                ->selectRaw("SUM(quantity) as total")
                ->groupBy('product_id')
                ->first()?->total ?? 0;
            
                $product['quantityTotal'] = ImportProduct::where('product_id', $product->id)
            ->selectRaw("SUM(quantity) as total")
            ->groupBy('product_id')
            ->first()?->total ?? 0;

            $product['remaining'] = $product['quantityTotal'] - $product['quantitySold'];
            $i++;
        }
        
        $products = $products->sortByDesc('id');
        return response()->json($products->values()->toArray());
    }
}
