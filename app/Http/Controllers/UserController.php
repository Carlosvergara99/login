<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class UserController extends Controller
{

    public function index()
    {
        $data = User::all('id','name','email');
        return response()->json(['users' =>$data]);
    }

   public function create(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "message" => "email ya existe"
            ], 403);
        }

      $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
      return response()->json([
        "user" => $user
        ], 201);

   }

     public function update(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,'.$request->id,
        ]);
        if ($validator->fails())
        {
            return response()->json([
                "message" => "email ya existe"
            ], 403);
        }
        
        $user = User::find($request->id);
        if ($request->password) {
            $user->update($request->only('name','email'));

        }else{
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ] );

        }

        return response()->json([
            "user" => $user
            ], 201);
     }

     public function delete(Request $request)
     {

        DB::table('personal_access_tokens')
           ->where('tokenable_id', $request->id)
           ->delete();
    
        $user = User::find($request->id);
        $user->delete();

        return response()->json([
            "message" => "user deleted"
            ], 201);
     }

}
