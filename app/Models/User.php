<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
