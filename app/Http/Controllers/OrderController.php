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
        return response()->json([
            'data' => Order::all()
        ]);
    }
    public function detail($id){
        return response()->json([
            'data' => Order::find($id)
        ]);
    }
    public function index(Request $request)
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderFilter(Request $request)
    {
        $check_in = $request->check_in;
        $guest_name = $request->guest_name;

        // $query = Order::query();

        // if($guest_name){
        //     $query = $query->whereHas('guest_name', function ($query) use ($guest_name){
        //         $query->where('guest_name', 'like', '%'.$guest_name.'%');
        //     });
        // }
        // $orders = $query->get();

        $orders= [];


        if($check_in == null ){
            $orders = DB::table("orders")
        ->select("guest_name", "customer_name","customer_email",'rooms_amount','check_in','status',)
        ->where("guest_name","=",$guest_name)
        ->get();
        } 
        
        if($guest_name == null ){
            $orders = DB::table("orders")
        ->select("guest_name", "customer_name","customer_email",'rooms_amount','check_in','status',)
        ->where("check_in","=",$check_in)
        ->get();
        }

        if($check_in != null && $guest_name != null){
            $orders = DB::table("orders")
        ->select("guest_name", "customer_name","customer_email",'rooms_amount','check_in','status',)
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

        // // mengambil string tanggal
        // $date = date('Y-m-d');
        // $timestamp = strtotime($date);
        // $result = date("dm", $timestamp);
        // // mengambil order id
        // $order_id = Order::latest()->first();
        // $order_id = $order_id->order_id;
        // // order id to str
        // $str_order_id = strval($order_id);

        // $order_number = $str_order_id . $result;
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
        // $room = Rooms::Select('room_number')->where('type_id', $type_id)->get();
        // $price = DB::table('orders_details as dp')
        //     ->join('rooms as km', 'dp.room_id', '=', 'km.room_id')
        //     ->join('type as tk', 'km.type_id', '=', 'tk.type_id')
        //     ->select('dp.orders_details_id', 'tk.price')
        //     ->where('dp.orders_details_id', 1)
        //     ->first();

        
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
        }
            

        $data = Order::latest()->first();
        $order_id = $data->order_id;


$booked_rooms = DB::table('orders_details')
                ->join('rooms', 'orders_details.room_id', '=', 'rooms.room_id')
                ->join('orders', 'orders_details.order_id', '=', 'orders.order_id')
                ->where('orders_details.order_id' ,'=', $order_id)
                ->select('rooms.room_id', 'rooms.room_number', 'orders.check_in', 'orders.check_out', 'orders.customer_name')
                ->groupBy('rooms.room_id', 'rooms.room_number', 'orders.check_in', 'orders.check_out', 'orders.customer_name')
                ->get();



        return response()->json([
            'message' => 'Success!!',
            'data' => $data,
            'room selected' =>$booked_rooms,
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