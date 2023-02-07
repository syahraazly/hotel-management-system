<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreOrders_DetailRequest;
use App\Http\Requests\UpdateOrders_DetailRequest;
use App\Models\Order;

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

        $detailData = Orders_Detail::find();

        return response()->json([
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
