<?php

namespace App\Http\Controllers;

use App\Events\GroupMsgEvent;
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
            'name' => 'required'
        ]);

        $response = Group::create($data);

        return response()->json($response);
    }

    public function addUserToGroup(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'group_id' => 'required|exists:groups,id'
            ]);

            $response = GroupUsers::create($data);

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
        $group = GroupUsers::find($group_id)
            ->join('users','users.id','=','group_users.user_id')
            ->select('group_users.*','users.name')
        ->get();

        return response()->json($group);
    }

    public function searchUsers(Request $request)
    {
        $data = $request->validate([
            'search' => 'required'
        ]);

        $search = User::where('name', 'like', '%'.$data['search'].'%')
            ->select('name')
            ->get();

        return response()->json($search);
    }

    public function myChatsList($user_id)
    {
        $group = GroupUsers::where('user_id',$user_id)
            ->join('groups','groups.id','=','group_users.group_id')
            ->select('groups.name')->paginate(5);
        $oneToOne = OneToOne::where('from_user_id',$user_id)
            ->join('users','users.id','=','one_to_ones.to_user_id')
            ->select('one_to_ones.to_user_id','users.name')
            ->distinct()
            ->paginate(5);

        return response()->json(['data'=>[$group ,$oneToOne]]);
    }

    public function groupChats($group_id)
    {
        $group = GroupMsg::where('group_id',$group_id)
            ->select('user_id','message')
            ->paginate(10);

        return response()->json($group);
    }
}
