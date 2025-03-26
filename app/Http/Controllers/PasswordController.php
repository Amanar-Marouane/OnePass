<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function index()
    {
        $passwords = Password::where('user_id', operator: 1)->get();
        return view('index', compact('passwords'));
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'password' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'username' => 'required|string|max:255',
        ]);

        // dd($request);

        Password::create([
            'password' => $fields['password'],
            'username' => $fields['username'],
            'service' => $fields['service'],
            'user_id' => $fields['user_id'] ?? 1,
        ]);

        return redirect()->route('passwords.index');
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'password_id' => 'required|exists:passwords,id',
            'password' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'username' => 'required|string|max:255',
        ]);

        $pass = Password::findOrFail($fields['password_id']);

        if ($pass->user_id !== 1) {
            return redirect()->route('passwords.index');
        }

        $pass->update([
            'password' => $fields['password'],
            'username' => $fields['username'],
            'service' => $fields['service'],
        ]);

        return redirect()->route('passwords.index');
    }

    public function destroy( $id){
        $password = Password::find($id);
        $password->delete();

        return redirect()->route('passwords.index');
    }
}
