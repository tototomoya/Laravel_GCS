<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Storage;
use App\File;
use Carbon\Carbon;

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
        $user = User::where('name', $name)->first();
        $session = $request->session();
        $session->put('owner', $user->name);
        //$session->put('owner', $name);
        $session->save();
        $file = $request->file('file');
        $content = mb_convert_encoding($file->get(), "UTF-8");
        $path = "test/" . $name . "." . $file->clientExtension();
        //Storage::disk('gcs')->put('test/' . $name . "." . $file->clientExtension(), $content);
        //Storage::disk('local')->put('test/' . $name . "." . $file->clientExtension(), $content);
        Storage::disk('local')->put($path, $content);

        File::insert(
            [
                'path' => $path,
                'password' => $user->password,
                'user_id' => $user->id,
                'updated_date' =>  Carbon::now()->formatLocalized('%Y年%m月%d日(%a)')
            ]);

        return "{$name}さまのファイルを登録しました。";
    }

    public function owner_file($id, $file_path) {
        try{
            //$content = Storage::disk('gcs')->get('test/' . $name . '.txt');
            $content = Storage::disk('local')->get("test/" . $file_path);
        } catch (Exception $e) {
            report($e);
        }
        return view('file', ['content' => $content, 'read_only', => False, 'id' => $id]);
    }
}
