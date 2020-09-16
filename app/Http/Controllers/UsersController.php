<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Storage;
use App\File;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class UsersController extends Controller
{
    public function user_content(Request $request, $user_name) {
            $user = User::where('name', $user_name)->first();
            $files = $user->files;
            return view('user', ['user' => $user, 'files' => $files]); 
    }

    public function users_index() {
        $users = User::simplePaginate(15);
        return view('users_index', ['users' => $users]);
    }

    public function upload(Request $request, $user_name) {
        $user = User::where('name', $user_name)->first();
        $file = $request->file('file');
        $content = mb_convert_encoding($file->get(), "UTF-8");
        //Storage::disk('gcs')->put('test/' . $name . "." . $file->clientExtension(), $content);
        $file_name = $file->getClientOriginalName();
        Storage::disk('local')->put($user->name . "/" . $file_name, $content);
        File::insert(
            [
                'path' => $user->name . "/" . $file_name,
                'password' => $user->password,
                'user_id' => $user->id,
                'updated_date' => Carbon::now()->formatLocalized('%Y年%m月%d日(%a) %H:%M')
            ]);

        return redirect()->action(
            'UsersController@user_content', ['name' => $user->name]
        );
    }

    public function owner_file($user_name, $file_name) {
        $file = File::where('path', $user_name . "/" . $file_name)->first();
        try{
            //$content = Storage::disk('gcs')->get('test/' . $name . '.txt');
            $content = Storage::disk('local')->get($user_name . "/" . $file_name);
        } catch (Exception $e) {
            report($e);
        }
        return view('file', ['content' => $content, 'read_only' => False, 'file' => $file]);
    }
}
