<?php

namespace App\Http\Controllers;

use App\Events\OnetoOneMsg;
use App\Models\OneToOne;
use Illuminate\Http\Request;

class OneToOneController extends Controller
{
    public function index(Request $request){
        try {
            $data = $request->validate([
                'from_user_id' => 'required|exists:users,id',
                'to_user_id' => 'required|exists:users,id',
                'message' => 'required'
            ]);

            $response = OneToOne::create($data);


            broadcast(new OnetoOneMsg($data['to_user_id'], $data['message']));

            return response()->json($response, 201);
        }catch (\Exception $e){
            return response()->json($e, 400);
        }
    }
}
