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

        $data = DB::table("type")
        ->leftJoin("rooms", function($join){
            $join->on("type.type_id", "=", "rooms.room_id");
        })
        ->leftJoin("orders_details", function($join)use ($date){
           
            $join->on("rooms.room_id", "=", "orders_details.room_id")
            ->whereBetween('orders_details.access_date',  [$date]);
        })
        ->select("type.type_name", "orders_details.access_date")
        ->whereNull("orders_details.access_date")
        ->get();


        return response()->json([
            $data   
        ]);
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
