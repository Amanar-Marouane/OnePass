<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class test extends Controller
{
    public function index(Request $request)
    {


        $machinetype = PHP_OS;
        if (stristr(PHP_OS, 'Darwin')) {
            $macAddress = exec('ifconfig en0 | awk \'/ether/ {print $2}\'');
        } else {
            $macAddress = exec('getmac');
        }


        return view('welcome', compact('macAddress'));
    }
}
