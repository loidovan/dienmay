<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::with('product')->orderBy('created_at', 'desc')->get();
        $i = 1;
        foreach ($reviews as $review) {
            $review['stt'] = $i;
            $i++;
        }
        return \response()->json($reviews);
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
        DB::beginTransaction();
        try {
            $review = new Review();
            $review->product_id = $request->product_id;
            $review->customer_name = $request->customer_name;
            $review->customer_phone = $request->customer_phone;
            $review->content = $request->content;
            $review->rating = $request->rating;
            $review->like_qty = 0;
            $review->unlike_qty = 0;
            $review->created_at = date('Y-m-d H:i:s');
            $review->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Gửi đánh giá thành công'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $productId
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        $reviews = Review::where('status', 1)
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reviews);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (!empty($request->type)) {
            $review = Review::where('id', $id)->first();
            $review[$request->type] = $review[$request->type] + 1;
            $review->save();
            return response()->json([
                'message' => 'Cập nhật thành công',
            ], 200);
        }

        if (auth()->user()->can('edit-review')) {
            $reviews = Review::whereIn('id', $request->ids)->get();
            foreach ($reviews as $order) {
                $order->status = $request->status;
                $order->save();
            }
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->can('delete-review')) {
            $ids = explode(',', $id);
            $order = Review::whereIn('id', $ids)->delete();
            return response()->json([
                'message' => 'Xóa thành công',
            ], 200);
        }
        return response()->json([
            'message' => 'Bạn không có quyền xóa',
        ], 401);
    }
}
