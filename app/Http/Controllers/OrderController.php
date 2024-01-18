<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderDetails;
use App\Models\Cart;
use App\Models\ImportProduct;
use App\Models\Product;
use HoangPhi\VietnamMap\Models\District;
use HoangPhi\VietnamMap\Models\Province;
use HoangPhi\VietnamMap\Models\Ward;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('customer', 'orderDetails', 'orderDetails.product', 'orderDetails.order', 'orderDetails.order.customer')
            ->orderBy('id', 'desc')->get();
        $i = 1;
        foreach ($orders as $order) {
            $order['stt'] = $i;
            $i++;
        }
        return \response()->json($orders);
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
            $checkExistCustomer = Customer::where('phone', $request->form['phone'])->first();
            if ($checkExistCustomer) {
                $customer_id = $checkExistCustomer->id;
            } else {
                $customer = new Customer();
                $customer->name = $request->form['name'];
                $customer->gender = $request->form['gender'];
                $customer->phone = $request->form['phone'];
                $customer->password = $this->generateRandomString(4);
                $customer->created_at = date('Y-m-d H:i:s');
                $customer->save();
                $customer_id = $customer->id;
            }

            $order = new Order();
            $order->customer_id = $customer_id;
            if ($request->form['wayReceive'] == 'Giao tận nơi') {
                $province_name = Province::select('name')->where('id', $request->province_id)->first();
                $district_name = District::select('name')->where('id', $request->district_id)->first();
                $ward_name = Ward::select('name')->where('id', $request->ward_id)->first();
                $order->address = $request->form['address'] . ', ' . $ward_name['name'] . ', ' . $district_name['name'] . ', ' . $province_name['name'];
            } else {
                $order->address = 'Nhận tại siêu thị';
            }
            if ($request->form['otherPeopleReceive'] == true) {
                $order->otherpeople_name = $request->form['otherPeopleName'];
                $order->otherpeople_phone = $request->form['otherPeoplePhone'];
                $order->otherpeople_gender = $request->form['otherPeopleGender'];
            }
            $order->way_receive = $request->form['wayReceive'];
            $order->payment = $request->form['payment'];
            $order->note = $request->form['note'];
            $order->created_at = date('Y-m-d H:i:s');
            $order->save();

            foreach ($request->carts as $item) {
                $product = Product::where('id', $item['product_id'])->first();
                $product['quantitySold'] = OrderDetails::where('product_id', $item['product_id'])
                ->selectRaw("SUM(quantity) as total")
                ->groupBy('product_id')
                ->first()?->total ?? 0;
            
                $product['quantityTotal'] = ImportProduct::where('product_id', $item['product_id'])
                ->selectRaw("SUM(quantity) as total")
                ->groupBy('product_id')
                ->first()?->total ?? 0;
        
                $product['remaining'] = $product['quantityTotal'] - $product['quantitySold'];

                if ($product['remaining'] <= 0) {
                    DB::rollback();
                    return response()->json([
                        'message' => "Sản phẩm " . $product->name . " đã hết hàng!",
                    ], 500);
                }

                $orderdetails = new OrderDetails();
                $orderdetails->order_id = $order->id;
                $orderdetails->product_id = $item['product_id'];
                $orderdetails->quantity = $item['quantity'];
                $orderdetails->save();
                Cart::where('id', $item['id'])->delete();
            }

            DB::commit();

            if ($request->form['payment'] == "Điện tử") {
                return response()->json(['url' => $this->checkout($request->hostName, $order->id, $request->totalPrice)]); 
            }

            return response()->json([
                'message' => 'Đặt hàng thành công',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($year)
    {
        $order = DB::select('select month(o.created_at) as month, year(o.created_at) as year, sum(p.price*od.quantity) as total
        from orders o JOIN order_details od ON od.order_id = o.id
        join products p on p.id = od.product_id 
        WHERE year(o.created_at) = ' . $year . '  
        AND o.status = 2
        GROUP by month(o.created_at), YEAR(o.created_at)');
        return \response()->json($order);
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
        if (auth()->user()->can('delete-order')) {
            $ids = explode(',', $id);
            $order = Order::whereIn('id', $ids)->delete();
            return response()->json([
                'message' => 'Xóa thành công',
            ], 200);
        }
        return response()->json([
            'message' => 'Bạn không có quyền xóa',
        ], 401);
    }

    public function handleOrder(Request $request)
    {
        $orders = Order::whereIn('id', $request->ids)->get();
        foreach ($orders as $order) {
            $order->status = $request->status;
            $order->save();
        }
        return response()->json([
            'message' => 'Cập nhật thành công',
        ], 200);
    }

    function generateRandomString($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function checkout($hostName, $orderId, $totalPrice)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = $hostName . "/vnpay_success";
        $vnp_TmnCode = "3AQNUB67"; //Mã website tại VNPAY 
        $vnp_HashSecret = "ZBWVYNNZDPMGCGNGAKGPKLEKOQDGXCKH"; //Chuỗi bí mật

        $vnp_TxnRef = strval($orderId); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán hoá đơn';
        $vnp_OrderType = "Điện máy Như Ý";
        $vnp_Amount = $totalPrice * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        // }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            return $returnData['data'];
        }
    }
}
