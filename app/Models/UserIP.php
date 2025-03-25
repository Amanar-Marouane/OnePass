<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserIP extends Model
{
     use HasFactory;


     protected $table = "users_ip";
     protected $fillable = [
        'user_id',
        'ip_address'
     ];

     public function user()
     {
         return $this->belongsTo(User::class);
     }
}

