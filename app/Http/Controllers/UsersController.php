<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function user_content($name) {
        $user = User::where('name', $name)->first();
        return view('user', ['user' => $user]);
    }

    public function users_index() {
        $users = User::simplePaginate(15);
        return view('users_index', ['users' => $users]);
    }

    public function upload(Request $request, $name) {
        $file = $request->file('file');
        $content = mb_convert_encoding($file->get(), "UTF-8");
        Storage::disk('gcs')->put('test/' . $name . "." . $file->clientExtension(), $content);
        return "{$name}さまのファイルを登録しました。";
    }
}
