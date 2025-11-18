<?php

namespace App\Http\Controllers;

use App\Events\GroupMsgEvent;
use App\Models\ChatRoom;
use App\Models\Group;
use App\Models\GroupMsg;
use App\Models\GroupUsers;
use App\Models\OneToOne;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function groupCreate(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        $response = Group::create($data);

        $group_id = $response->id;

        $groupUser = GroupUsers::create([
            'group_id' => $group_id,
            'user_id'=>$data['user_id']
        ]);

        return response()->json([
           'group name'=> $response,
            'members'=>$groupUser
        ]);
    }

    public function addUserToGroup(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'group_id' => 'required|exists:groups,id',
                'added_by' => 'required|exists:users,id'
            ]);

            if ($data['user_id'] == $data['added_by']) {
                return response()->json([
                    'error' => "You can't add yourself"
                ],422);
            }
            $response = GroupUsers::firstOrCreate($data);

            return response()->json($response);
        } catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function groupMsg(Request $request){
        try {
            $data = $request->validate([
                'message' => 'required',
                'group_id' => 'required|exists:groups,id',
                'user_id' => 'required|exists:users,id'
            ]);

            $response = GroupMsg::create($data);

            broadcast(new GroupMsgEvent($data['group_id'],$data['user_id'], $data['message']));

            return response()->json($response);
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function groupMembers($group_id)
    {
        try {
            $group = GroupUsers::where('group_users.group_id', $group_id)
                ->join('users', 'users.id', '=', 'group_users.user_id')
                ->select('group_users.user_id', 'users.name')
                ->paginate(10);

            return response()->json($group);
        }
        catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function searchUsers(Request $request)
    {
        try {
            $data = $request->validate([
                'search' => 'required'
            ]);

            $search = User::where('name', 'like', '%' . $data['search'] . '%')
                ->select('id','name')
                ->paginate(10);

            return response()->json($search);
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function myChatsList($user_id)
    {
        try {
            $group = GroupUsers::where('group_users.user_id', $user_id)
                ->join('groups', 'groups.id', '=', 'group_users.group_id')
                ->select('groups.name')
                ->latest()
                ->paginate(5);
//            ->get();

            $oneToOne = ChatRoom::where('chat_rooms.from_user_id', $user_id)
                ->orWhere('chat_rooms.to_user_id', $user_id)
                ->join('users', 'users.id', '=', 'chat_rooms.to_user_id')
                ->select('chat_rooms.from_user_id', 'users.name')
                ->latest()
                ->paginate(5);
//            ->get();

            return response()->json(['data' => [$group, $oneToOne]]);
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function groupChats($group_id)
    {
        try {
            $group = GroupMsg::where('group_id', $group_id)
                ->join('users', 'users.id', '=', 'group_msgs.user_id')
                ->select('group_msgs.user_id', 'users.name', 'group_msgs.message', 'group_msgs.created_at')
                ->latest()
                ->paginate(10);

            return response()->json($group);
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
