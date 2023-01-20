<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
       
        return response()->json([
            'data' => Type::all()
        ]);
    }
    public function detail($id)
    {
        return response()->json([
            'data' => Type::find($id)
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request,[
            'type_name' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'photo' => 'required',
        ]);

        Type::create([
            'type_name' => $request->type_name,
            'price' => $request->price,
            'desc' => $request->desc,
            'photo' => $request->photo,
        ]);

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
        $this->validate($request,[
            'type_name' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'photo' => 'required',
        ]);


        Type::where('type_id',$id)->update([
            'type_name'    =>$request->type_name,
            'price'    =>$request->price,
            'desc'    =>$request->desc,
            'photo'    =>$request->photo,
        ]);

        $this->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Type::find($id);
        $data->delete();
    }
}
