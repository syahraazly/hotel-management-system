<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Orders_Detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StorePemesananRequest;
use App\Http\Requests\UpdatePemesananRequest;
use App\Models\Rooms;
use App\Models\Type;
use App\Policies\OrdersDetailPolicy;
use Dflydev\DotAccessData\Data;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show (){
        $total_booking = DB::table('orders')->count();
        $check_in = DB::table('orders')->where('status', 'Check In')->count();

        $total_revenue = DB::table('orders_details')
        ->join('rooms', 'orders_details.room_id', '=', 'rooms.room_id')
        ->join('orders', 'orders_details.order_id', '=', 'orders.order_id')
        ->leftJoin('type', 'rooms.type_id', '=', 'type.type_id')
        ->select(DB::raw('SUM(type.price * DATEDIFF(orders.check_out, orders.check_in)) as total_income'))
        ->first();

        return response()->json([
            'data' => Order::all(),
            'booking' => $total_booking,
            'data_check' => $check_in,
            'total_income' => number_format($total_revenue->total_income, 0, ',', '.')
        ]);
    }
    public function detail($id){
        return response()->json([
            'data' => Order::find($id)
        ]);
    }
   
    public function orderFilter(Request $request)
    {
        $check_in = $request->check_in;
        $guest_name = $request->guest_name;

        $orders= [];


        if($check_in == null ){
            $orders = DB::table("orders")
        ->select("guest_name", "customer_name","customer_email",'rooms_amount','check_in','status', 'order_number')
        ->where("guest_name", "like", "%$guest_name%")
        ->get();
        } 
        
        if($guest_name == null ){
            $orders = DB::table("orders")
        ->select("guest_name", "customer_name","customer_email",'rooms_amount','check_in','status', 'order_number')
        ->where("check_in","=",$check_in)
        ->get();
        }

        if($check_in != null && $guest_name != null){
            $orders = DB::table("orders")
            ->select("guest_name", "customer_name","customer_email",'rooms_amount','check_in','status', 'order_number')
            ->where(function($query) use ($check_in,$guest_name) {
                $query->where("guest_name","=",$guest_name)
                    ->orWhere("check_in","=",$check_in);
            })
            ->get();
        } 

        return response()->json(([
            'data' => $orders
        ]));
    }
    public function create(Request $request)
    {
        $this->validate($request,[
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'check_in' => 'required',
            'check_out' => 'required',
            'guest_name' => 'required',
            'rooms_amount' => 'required',
            'type_id' => 'required',
        ]);

        $random = mt_rand(1000, 9999);

        $order_number = $random;

        $type_id = $request->type_id;
        $customer_name = $request->customer_name;
        $rooms_amount = $request->rooms_amount;


        $check_in = $request->check_in;
        $check_out = $request->check_out;

        $date = [$check_in,$check_out];

        $fdate = $request->check_in;
        $tdate = $request->check_out;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');

        $roomdata = DB::table('type')
        ->join('rooms', 'type.type_id', '=', 'rooms.type_id')
        ->leftJoin('orders_details', function($join) use ($date){
            $join->on('rooms.room_id', '=', 'orders_details.room_id')
                ->whereBetween('orders_details.access_date', [$date]);
             })
        ->where('type.type_id', $type_id)
        ->whereNull('orders_details.access_date')
        ->orderBy('rooms.room_id', 'asc')
        ->select('rooms.room_id', 'type.type_name')
        ->limit(1)
        ->get()
        ->first();

        $type_name = Type::find($type_id);
        $type_name = $type_name->type_name;

        $emptyRoom = DB::table('type')
        ->select('type.type_name', 'orders_details.access_date','type.photo_name','type.desc','type.price')
        ->leftJoin('rooms', 'type.type_id', '=', 'rooms.type_id')
        ->leftJoin('orders_details', function ($join) use ($date) {
            $join->on('rooms.room_id', '=', 'orders_details.room_id')
                ->whereBetween('orders_details.access_date', $date);
            })
        ->whereNull('orders_details.access_date')
        ->where('type_name','=',$type_name)
        ->count();

        if($emptyRoom<$rooms_amount){
            return response()->json([
                'message' => 'Jumlah kamar yang dipesan melebihi kamar yang tersedia',
                'Jumlah tersedia' => $emptyRoom
            ]);
        }

        Order::create([
            'order_number' => $order_number,
            'customer_name' =>$request->customer_name ,
            'customer_email'=>$request->customer_email,
            'check_in' =>$request->check_in,
            'check_out' =>$request->check_out,
            'guest_name' =>$request->guest_name,
            'rooms_amount' =>$request->rooms_amount,
            'type_id' =>$request->type_id,
        ]);

        // mencari order id
        $order_id = Order::latest()->first();
        $order_id = $order_id->order_id;

        //mencari room Orders_Detail
        $type_id = $request->type_id;
        
        $room_id = $roomdata->room_id;
        $room_price = Type::find($type_id);
        $room_price = $room_price->price;
        
        $current_room_id = $room_id;

        for ($room = 1; $room <= $rooms_amount; $room++) {
            for ($i = 0; $i < $days; $i++) {
                $detail = new Orders_Detail();
                $detail->order_id = $order_id;
                $detail->room_id = $current_room_id;
                $detail->access_date = $fdate;
                $detail->price = $room_price;
                $detail->save();
                $fdate = date("Y-m-d", strtotime('+1 days', strtotime($fdate)));  
            }
            $current_room_id++;
            $fdate = $request->check_in;
        }
            
        $data = Order::latest()->first();
        $order_id = $data->order_id;

        $booked_rooms = DB::table('orders_details')
        ->join('rooms', 'orders_details.room_id', '=', 'rooms.room_id')
        ->join('orders', 'orders_details.order_id', '=', 'orders.order_id')
        ->leftJoin('type', 'rooms.type_id', '=', 'type.type_id')
        ->where('orders_details.order_id', '=', $order_id)
        ->select('rooms.room_id', 'rooms.room_number', 'type.type_name', 'type.price', 'orders.check_in', 'orders.check_out', 'orders.customer_name')
        ->groupBy('rooms.room_id', 'rooms.room_number', 'type.type_name', 'type.price', 'orders.check_in', 'orders.check_out', 'orders.customer_name')
        ->get();

        $price = Type::find($type_id);
        $price = $price->price;

        $grand_total = $rooms_amount*$days*$price;

        return response()->json([
            'message' => 'Success!!',
            'data' => $data,
            'room selected' =>$booked_rooms,
            'Grand total' => $grand_total
        ]);
    }

    public function upstatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        Order::where('order_id', $id)->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Success Update Status Order!!',
            'data' => Order::find($id)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePemesananRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'order_number' => 'required',
            'customer' => 'required',
            'customer_email' => 'required',
            'order_date' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'guest_name' => 'required',
            'rooms_amount' => 'required',
            'type_id' => 'required',
            'user_id' => 'required',
        ]);
        Order::create([
            'order_number' => $request->order_number,
            'customer' => $request->customer,
            'customer_email' => $request->customer_email,
            'order_date' => $request->order_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guest_name' => $request->guest_name,
            'rooms_amount' => $request->rooms_amount,
            'type_id' => $request->type_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json([
            'message' => 'Success!!',
            'data' => Order::all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $pemesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePemesananRequest  $request
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $pemesanan)
    {
        //
    }
}