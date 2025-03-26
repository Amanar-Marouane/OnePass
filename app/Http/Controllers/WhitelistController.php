<?php

namespace App\Http\Controllers;

use App\Models\WhiteList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WhitelistController extends Controller
{
    public function Store(Request $request)
    {
        $validated = $request->validate([
            'ip' => ['required', 'ipv4']
        ]);
        $whitelist = WhiteList::create(array_merge($validated, [
            'user_id' => $request->user()->id
        ]));
        return response()->json([
            'message' => 'whitelist created successfully',
            'data' => $whitelist
        ]);
    }

    public function update(Request $request, string $id)
    {
        $whitelist = WhiteList::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$whitelist) {
            return response()->json([
                "message" => "Whitelist doesn't exist :/"
            ]);
        }
        $validated = $request->validate([
            'ip' => ['required', 'ipv4']
        ]);
        $whitelist->update($validated);
        return response()->json([
            "message" => "whitelist Updated",
            "datat" => $whitelist
        ]);
    }
    public function destroy(string $id, Request $request){
        $whitelist = WhiteList::where('id',$id)->where('user_id',$request->user()->id)->first();
        if(!$whitelist){
            return response()->json([
                "message"=>"Whitelist Doesn't Exist :/"
            ]);
        }
        $whitelist->delete();
        return response()->json([
            "message"=>"ip deleted succesfully"
        ]);
    }
}
