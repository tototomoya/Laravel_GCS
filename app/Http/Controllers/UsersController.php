<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\File;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class UsersController extends Controller
{
    public function user_content(Request $request, $user_name) {
        $owner = session('owner') == $user_name ? 'あなたはオーナーです。' : 'あなたはオーナーじゃないです。';
        $user = User::where('name', $user_name)->first();
        $files = $user->files;
        return view('user', ['user' => $user, 'files' => $files, 'owner' => $owner]);
    }

    public function users_index() {
        $owner = session('owner');
        if(!$owner) {
            return view('user_login');
        } else {
            $users = User::simplePaginate(15);
            return view('users_index', ['users' => $users]);
        }
    }

    public function user_login(Request $request) {
        $user_name = $request->input('name');
        $password = $request->input('password');
        if($user_name && $password) {
            $user = User::where('name', $user_name)->first() == null ? null : User::where('name', $user_name)->first();
            if($password == $user->password) {
                $files = $user->files;
                $files_data = serialize($files);
                $user_data = serialize($user);
                session(['owner' => $user->name]);
                return redirect()->action(
                    'UsersController@user_content', ['name' => $user->name]
                );
            } else {
                return back();
            }
        }
    }
}
