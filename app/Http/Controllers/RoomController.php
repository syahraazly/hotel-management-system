<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function show(){
        $room = Rooms::all();
        return response()->json($room);
    }
    public function detail(){

    }
    public function store(Request $request){
        $this->validate($request,[
            'type_id' => 'required',
            'room_number' => 'required',
            'room_type' => 'required',
            'status' => 'required',
        ]);

        Rooms::create([
            'type_id' => $request->type_id,
            'room_number' => $request->room_number,
            'room_type' => $request->room_type,
            'status' => $request->status,
        ]);
        return response()->json([
            'message' => 'Success!!',
            'data' => Rooms::all()
        ]);
    }
}
