<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OneToOneController;


Route::post('groups/create', [GroupController::class, 'groupCreate']);
Route::post('groups/add/users', [GroupController::class, 'addUserToGroup']);
Route::post('groups/add/messages', [GroupController::class, 'groupMsg']);

Route::post('one/to/one',[OneToOneController::class, 'index']);
Route::get('group/members/{group_id}',[GroupController::class, 'groupMembers']);
Route::get('search/users', [GroupController::class, 'searchUsers']);
Route::get('my/chats/{user_id}', [GroupController::class, 'myChatsList']);
Route::get('group/chats/{group_id}', [GroupController::class, 'groupChats']);
