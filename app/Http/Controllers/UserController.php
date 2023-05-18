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
        $data = User::all();
        return response()->json(['user' =>$data]);
    }

   public function create(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:prize'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "message" => "email ya existe"
            ], 403);
        }

      $user = User::created($request->except('token'));
      return response()->json([
        "message" => "prize record created"
        ], 201);

   }

   public function edit(Request $request)
   
   {
        $user = User::find($request->id);

        return response()->json([
            "data" => $user
            ], 201);
   }

     public function update(Request $request)
     {
        
        $user = User::find($request->id);
        $user->update($request->except('token', 'id'));

        return response()->json([
            "message" => "user updated"
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
