<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WhiteList extends Model
{
    protected $table = 'whitelist';
    protected $fillable = ['ip','user_id'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
