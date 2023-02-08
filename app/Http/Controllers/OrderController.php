<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Orders_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePemesananRequest;
use App\Http\Requests\UpdatePemesananRequest;
use App\Models\Rooms;
use App\Policies\OrdersDetailPolicy;
use Dflydev\DotAccessData\Data;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        // mengambil string tanggal
        $date = date('Y-m-d');
        $timestamp = strtotime($date);
        $result = date("dm", $timestamp);
        // mengambil order id
        $order_id = Order::latest()->first();
        $order_id = $order_id->order_id;
        // order id to str
        $str_order_id = strval($order_id);

        $order_number = $str_order_id . $result;

        $type_id = $request->type_id;
        $customer_name = $request->customer_name;
        $rooms_amount = $request->rooms_amount;
        $hash = strlen($customer_name);

        $rooms_amount = DB::table('type')->count();

        $fdate = $request->check_in;
        $tdate = $request->check_out;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');//now do whatever you like with $days

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


        $room_id = Rooms::Select('room_id')->where('type_id', $type_id)->get()->first();
        $room_id = $room_id->room_id;
  

        // mencari order id
        $order_id = Order::latest()->first();
        $order_id = $order_id->order_id;

        //mencari room Orders_Detail
        $type_id = $request->type_id;
        $room = Rooms::Select('room_number')->where('type_id', $type_id)->get();
       
    

        for($i = 0; $i <$days; $i++){
            $detail = new Orders_Detail();
            $detail->order_id = $order_id;
            $detail->room_id = $room_id;
            $detail->access_date = $fdate;
            $detail->price = 50000;
            $detail->save();
            $fdate = date("Y-m-d",strtotime('+1 days',strtotime($fdate)));
        }

        $data = Order::latest()->first();

        return response()->json([
            'message' => 'Success!!',
            'data' => $data,
            'room available' =>$room,
            'room_number' => $order_number
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

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
