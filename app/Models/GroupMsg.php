<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMsg extends Model
{
    protected $fillable = [
        'message',
        'group_id',
        'user_id'
    ];
}
