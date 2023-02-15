<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Type;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function detailType($type_id){
        $room = Rooms::join('type', 'rooms.type_id', '=', 'type.type_id')
                ->select('rooms.*', 'type.type_name')
                ->where('rooms.type_id', $type_id)
                ->get();
        return $room;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'type_name' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $photo_name = $request->file('photo')->getClientOriginalName();
 
        $photo_path = $request->file('photo')->store('images');

        $location = 'images';

        $file = $request->file('photo');
        $file->move($location,$photo_name);


        Type::create([
            'type_name' => $request->type_name,
            'price' => $request->price,
            'desc' => $request->desc,
            'photo_name'    =>$photo_name,
            'photo_path'    =>$photo_path,
        ]);
        return response()->json([
            'message' => 'Success!!',
            'data' => Type::all()
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'type_name' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'photo' => 'required'
        ]);

        $type = Type::findOrFail($id);

        if ($request->hasFile('photo')) {
            $photo_name = $request->file('photo')->getClientOriginalName();
            $photo_path = $request->file('photo')->store('images');

            Storage::delete('public/' . $type->photo_path);

            // Hapus foto lama sebelum memindahkan foto baru ke direktori public/images
            if (file_exists(public_path('images/' . $type->photo_name))) {
                unlink(public_path('images/' . $type->photo_name));
            }

            // Pindahkan foto baru ke direktori public/images
            $location = 'images';
            $request->file('photo')->move($location, $photo_name);
        } else {
            $photo_name = $type->photo_name;
            $photo_path = $type->photo_path;
        }

        Type::where('type_id', $id)->update([
            'type_name' => $request->type_name,
            'price' => $request->price,
            'desc' => $request->desc,
            'photo_name' => $photo_name,
            'photo_path' => $photo_path,
        ]);

        return response()->json([
            'message' => 'Success Update Data!',
            'data' => Type::find($id)
        ]);
    }


    public function destroy($id)
    {
        $data = Type::where('type_id',$id)->delete();

        return response()->json([
            'message' => 'Success Delete Data!',
        ]);
    }
}
