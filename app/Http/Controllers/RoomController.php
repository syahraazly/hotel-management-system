<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function show(){
        $room = Rooms::all();
        return response()->json($room);
    }
    public function detail(){

    }
    public function store(Request $request){
        // $validator = $request->validate([
        //     'type_id' => 'required|exists:type, type_id',
        //     'room_number' => 'required',
        //     'room_type' => 'required',
        //     'status' => 'required',
        // ]);
        // if(!isset($validator['type_id'])){
        //     return response()->json(['message' => 'Please insert room type id'], 400);
        // }
        // $room = new Rooms;
        // $room->type_id = $validator['type_id'];
        // $room->room_number = $validator['room_number'];
        // $room->room_type = $validator['room_type'];
        // $room->status = $validator['status'];
        // $room->save();

        // return response()->json(['message' => 'Successfully created kamar!'], 201);
        $this->validate($request,[
            'type_id' => 'required',
            'room_number' => 'required',
            'status' => 'required',
        ]);
        Rooms::create([
            'type_id' => $request->type_id,
            'room_number' => $request->room_number,
            'status' => $request->status,
        ]);
        return response()->json([
            'message' => 'Success!!',
            'data' => Rooms::all()
        ]);
    }
}
