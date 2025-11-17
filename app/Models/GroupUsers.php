<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUsers extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'added_by'
    ];
}
