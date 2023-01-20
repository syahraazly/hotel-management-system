<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        return response()->json([
            'message' => 'Success!!'
        ]);
    }

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

        return response()->json([
            'message' => 'Success Update Data!',
            'data' => Type::find($id)
        ]);
    }

    public function destroy($id)
    {
        $data = Type::find($id);
        $data->delete();

        return response()->json([
            'message' => 'Success Delete Data!',
        ]);
    }
}
