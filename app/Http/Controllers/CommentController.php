<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::with(['commentDetails', 'product', 'commentDetails.user'])->orderBy('id', 'desc')->get();
        $i = 1;
        foreach ($comments as $comment) {
            $comment['stt'] = $i;
            $i++;
        }
        return response()->json($comments);
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
            $comment = new Comment();
            $comment->product_id = $request->product_id;
            $comment->customer_name = $request->customer_name;
            $comment->customer_phone = $request->customer_phone;
            $comment->content = $request->content;
            $comment->created_at = date('Y-m-d H:i:s');
            $comment->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Gửi bình luận thành công'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        $comments = Comment::where('status', 1)
            ->where('product_id', $productId)
            ->with(['commentDetails', 'product', 'commentDetails.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (!empty($request->content) && empty($request->status)) {
            $commentDetail = CommentDetails::where('comment_id', $id)->first();

            if ($commentDetail) {
                $commentDetail->content = $request->content;
                $commentDetail->created_at = date('Y-m-d H:i:s');
                $commentDetail->user_id = auth()->user()->id;
                $commentDetail->status = 1;
                $commentDetail->save();
            } else {
                $commentDetail = new CommentDetails();
                $commentDetail->comment_id = $id;
                $commentDetail->content = $request->content;
                $commentDetail->like_qty = 0;
                $commentDetail->unlike_qty = 0;
                $commentDetail->created_at = date('Y-m-d H:i:s');
                $commentDetail->user_id = auth()->user()->id;
                $commentDetail->status = 1;
                $commentDetail->save();
            }

            return response()->json([
                'message' => 'Cập nhật thành công',
            ], 200);
        }

        if (auth()->user()->can('edit-comment')) {
            $comments = Comment::whereIn('id', $request->ids)->get();
            foreach ($comments as $order) {
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
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
