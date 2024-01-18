<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ImportProduct;
use App\Models\OrderDetails;
use App\Models\Product;

class CartController extends Controller
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
        $product = Product::where('id', $request->product_id)->first();
        $product['quantitySold'] = OrderDetails::where('product_id', $request->product_id)
        ->selectRaw("SUM(quantity) as total")
        ->groupBy('product_id')
        ->first()?->total ?? 0;
    
        $product['quantityTotal'] = ImportProduct::where('product_id', $request->product_id)
        ->selectRaw("SUM(quantity) as total")
        ->groupBy('product_id')
        ->first()?->total ?? 0;

        $product['remaining'] = $product['quantityTotal'] - ($product['quantitySold'] + 1);

        if ($product['remaining'] <= 0) {
            return response()->json([
                'message' => 'Sản phẩm hết hàng, xin mời chọn sản phẩm khác',
            ], 500);
        }

        $checkExistProduct = Cart::where('product_id', $request->product_id)->where('cart_id', $request->cart_id)->first();
        if ($checkExistProduct) {
            $checkExistProduct->quantity = ($checkExistProduct->quantity + 1) > 5 ? ($checkExistProduct->quantity) : ($checkExistProduct->quantity + 1);
            $checkExistProduct->save();
            return response()->json([
                'message' => 'Thêm sản phẩm thành công',
            ], 200);
        } else {
            $cart = new Cart();
            $cart->product_id = $request->product_id;
            $cart->cart_id = $request->cart_id;
            $cart->created_at = date('Y-m-d H:i:s');
            $cart->save();
            return response()->json([
                'message' => 'Thêm sản phẩm thành công',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $cart = Cart::find($id);

        $product = Product::where('id', $cart->product_id)->first();
        $product['quantitySold'] = OrderDetails::where('product_id', $cart->product_id)
        ->selectRaw("SUM(quantity) as total")
        ->groupBy('product_id')
        ->first()?->total ?? 0;
    
        $product['quantityTotal'] = ImportProduct::where('product_id', $cart->product_id)
        ->selectRaw("SUM(quantity) as total")
        ->groupBy('product_id')
        ->first()?->total ?? 0;

        $product['quantityCart'] = Cart::where('product_id', $cart->product_id)
        ->selectRaw("SUM(quantity) as total")
        ->groupBy('product_id')
        ->first()?->total ?? 0;

        $product['remaining'] = $product['quantityTotal'] - ($product['quantitySold'] + $product['quantityCart'] + (int)$request->quantity);

        if ($product['remaining'] < 0) {
            return response()->json([
                'message' => 'Sản phẩm hết hàng, xin mời chọn sản phẩm khác',
            ], 500);
        }

        if ($request->quantity == '-1') {
            $cart->quantity = $cart->quantity > 1 ? $cart->quantity - 1 : 1;
        } else if ($request->quantity == '+1') {
            $cart->quantity = $cart->quantity < 5 ? $cart->quantity + 1 : $cart->quantity;
        }
        $cart->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();
    }

    public function getCart(Request $request)
    {
        $cart = Cart::with('product')->where('cart_id', $request->cart_id)->orderBy('id', 'desc')->get();
        return response()->json($cart);
    }

}
