<?php

namespace App\Http\Controllers;

use App\Events\OnetoOneMsg;
use App\Models\ChatRoom;
use App\Models\OneToOne;
use Illuminate\Http\Request;

class OneToOneController extends Controller
{
    public function index(Request $request){
        try {
            $data = $request->validate([
                'from_user_id' => 'required|exists:users,id',
                'chat_room_id' => 'required|exists:chat_rooms,id',
                'message' => 'required'
            ]);

            $response = OneToOne::create($data);

            broadcast(new OnetoOneMsg($data['chat_room_id'], $data['from_user_id'], $data['message']));

            return response()->json($response, 201);
        }catch (\Exception $e){
            return response()->json($e, 400);
        }
    }

    public function createChatRoom(Request $request)
    {
        try {
            $data = $request->validate([
                'from_user_id' => 'required|exists:users,id',
                'to_user_id' => 'required|exists:users,id',
            ]);

            $response = ChatRoom::firstOrCreate([
                'from_user_id' => min($data['from_user_id'], $data['to_user_id']),
                'to_user_id' => max($data['from_user_id'], $data['to_user_id']),
            ]);

            return response()->json($response, 201);
        }catch (\Exception $e){
            return response()->json($e, 400);
        }
    }

    public function allChats($chat_id)
    {
        $chatRoom = OneToOne::where('one_to_ones.chat_room_id', $chat_id)
            ->join('users', 'users.id', '=', 'one_to_ones.from_user_id')
            ->select('one_to_ones.*', 'users.name')
            ->latest()
            ->paginate(10);
//            ->get();

        return response()->json(['data' => $chatRoom]);
    }
}
