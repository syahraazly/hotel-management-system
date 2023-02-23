<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreOrders_DetailRequest;
use App\Http\Requests\UpdateOrders_DetailRequest;
use App\Models\Order;
use App\Models\Type;

class OrdersDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request,[
            'check_in' => 'date',
            'check_out' => 'date'
        ]);
        $check_in = $request->check_in;
        $check_out = $request->check_out;

        $date = [$check_in,$check_out];
        
        // $room = Rooms::Select('room_number')->where('type_id', $type_id)->get();

            $data = DB::table('type')
            ->select('type.type_id','type.type_name', 'orders_details.access_date','type.photo_name','type.desc','type.price')
            ->leftJoin('rooms', 'type.type_id', '=', 'rooms.type_id')
            ->leftJoin('orders_details', function ($join) use ($date) {
                $join->on('rooms.room_id', '=', 'orders_details.room_id')
                    ->whereBetween('orders_details.access_date', $date);
            })
            ->whereNull('orders_details.access_date')
            ->get();
            
            $groupedData = [];

foreach ($data as $item) {
    if (!isset($groupedData[$item->type_name])) {
        $groupedData[$item->type_name] = $item;
    }
}

$result = array_values($groupedData);


        return response()->json([
            'data' => $result
        ]);
    }


 public function  checkorder(Request $request){
        $this->validate($request,[
            'order_number' => 'required',
            'email' => 'email'
        ]);

        $email = $request->email; // ubah dengan email yang diinginkan
$order_number = $request->order_number; // ubah dengan order_number yang diinginkan

$results = DB::table('orders')
    ->join('orders_details', 'orders.order_id', '=', 'orders_details.order_id')
    ->join('rooms', 'orders_details.room_id', '=', 'rooms.room_id')
    ->join('type', 'rooms.type_id', '=', 'type.type_id')
    ->select('rooms.room_id', 'type.type_name', 'orders.check_in', 'orders.check_out', 'orders.guest_name', 'orders.customer_name')
    ->where('orders.customer_email', '=', $email)
    ->where('orders.order_number', '=', $order_number)
    ->get()->first();

return response()->json($results);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reciept($order_number)
    {
        $order_id = DB::table('orders')
        ->where('order_number','=',$order_number)
        ->value('order_id');
        
        $days = DB::table('orders_details')->select('access_date')
        ->where('order_id','=',$order_id)
        ->count();
       
        $booked_rooms = DB::table('orders_details')
        ->join('rooms', 'orders_details.room_id', '=', 'rooms.room_id')
        ->join('orders', 'orders_details.order_id', '=', 'orders.order_id')
        ->leftJoin('type', 'rooms.type_id', '=', 'type.type_id')
        ->where('orders_details.order_id', '=', $order_id)
        ->select('rooms.room_id', 'rooms.room_number', 'type.type_name', 'type.price', 'orders.check_in', 'orders.check_out', 'orders.customer_name')
        ->groupBy('rooms.room_id', 'rooms.room_number', 'type.type_name', 'type.price', 'orders.check_in', 'orders.check_out', 'orders.customer_name')
        ->get();

        $type_id = DB::table('orders')
            ->where('order_number','=',$order_number)
            ->value('type_id');
     
        $price = DB::table('type')
            ->where('type_id','=',$type_id)
            ->value('price');

        $grand_total = $days*$price;
        $data = Order::find($order_id);

        return response()->json([
            // 'message' => 'Success!!',
            'data' => $data,
            'room_selected' =>$booked_rooms,
            'grand_total' => $grand_total

        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrders_DetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrders_DetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orders_Detail  $orders_Detail
     * @return \Illuminate\Http\Response
     */
    public function show(Orders_Detail $orders_Detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orders_Detail  $orders_Detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders_Detail $orders_Detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrders_DetailRequest  $request
     * @param  \App\Models\Orders_Detail  $orders_Detail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrders_DetailRequest $request, Orders_Detail $orders_Detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders_Detail  $orders_Detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders_Detail $orders_Detail)
    {
        //
    }
}
