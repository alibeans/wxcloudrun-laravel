<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'owners';

    protected $fillable = [
        'openid',
        'name',
        'phone',
        'house_no',
        'id_tail',
        'member_no',
        'status',
    ];

    protected $hidden = [
        'id',
        'openid',
    ];
}
