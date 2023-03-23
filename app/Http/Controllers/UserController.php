<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
// use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid credentials'], 400);
            }
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'could not create token'], 500);
        }

        // return response()->json(compact('token'));
        return  $this->createNewToken($token);
    }

    public function logout(Request $request){

        $user = Auth::guard('api')->user();
        Auth::guard('api')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response([
            'message' => 'success logout'
        ]);      
        // $this->guard()->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // // $request->session()->invalidate();  
        // return response([
        //     'message' => 'success logout'
        // ]);      
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl'),
            'user' => auth()->user()
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'password_confirmation' => Hash::make($request->get('password_confirmation')),
            'role' => $request->get('role')
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),201);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } 
        catch (TokenExpiredException $e)
        {
            return response()->json(['token_expired'], 401);
        }
        catch (TokenInvalidException $e)
        {
            return response()->json(['token_invalid'], 401);
        }
        catch (JWTException $e)
        {
            return response()->json(['token_absent'], 400);
        }

        return response()->json(compact('user'));
    }

    public function show(){
        $total = DB::table('users')->count();
        $totalRecep = DB::table('users')->where('role', 'receptionist')->count();
        $totalAdmin = DB::table('users')->where('role', 'admin')->count();

        return response()->json([
            'total' => $total,
            'total_recep' => $totalRecep,
            'total_admin' => $totalAdmin,
            'user' => User::all()
        ]);
    }

    public function update(Request $request,$id){
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required'
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>Hash::make( $request->password),
            'role' => $request->role,
        ]);

        $data = User::find($id);

        return response([
            "message" => "Succesfully updated user",
            "user" => $data
        ]);
    }

    public function delete($id){
        User::where('id',$id)->delete();

        return response(["Data telah terhapus"]);
    }
}
