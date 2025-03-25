<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['user_id', 'user_agent', 'mac_address', 'is_verified'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getMacAddress()
    {
        if (stristr(PHP_OS, 'Darwin')) {
            return exec('ifconfig en0 | awk \'/ether/ {print $2}\'');
        } else {
            return exec('getmac');
        }
    }
}
