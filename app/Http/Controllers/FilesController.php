<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;
use App\File;
use Carbon\Carbon;

class FilesController extends Controller
{
    private $disk_type = 'public';

    public function re_name($user_name, $file_name, &$storage) {
        if($storage->exists($user_name . "/" . $file_name)) {
            $path_parts = pathinfo($file_name);
            $file_extension = "." . $path_parts["extension"];//memo.txt=>.txt
            $file_name_NoExtension = str_replace($file_extension, "", $file_name);//memo.txt=>memo
            preg_match('/(?<=\().*?(?=\))/', $file_name, $num);//memo(1).txt, $num[0]=1
            if ($num == []) {
                $num = array(0);
            }
            $num = (int)$num[0] + 1;
            $file_name = preg_replace('/\(.*/', '', $file_name);//memo(1)=>memo
            $file_name = $file_name . "(" . $num . ")" . $file_extension;
            $file_name = $this->re_name($user_name, $file_name, $storage);
            return $file_name;
        } else {
            return $file_name;
        }
    }

    public function upload(Request $request, $user_name) {
        $user = User::where('name', $user_name)->first();
        $upload = $request->file('file');
        //$content = mb_convert_encoding($upload->get(), "UTF-8");
        $content = $upload->get();
        $file_name = $upload->getClientOriginalName();
        $storage = Storage::disk($this->disk_type);
        //同名ファイルの場合は名前変更( memo.txt=>memo(1).txt )
        $file_name = $this->re_name($user_name, $file_name, $storage);
        $storage->put($user_name . "/" . $file_name, $content);
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

    public function read_file($user_name, $file_id) {
        $file = File::find($file_id);
        $storage = Storage::disk($this->disk_type);
        try{
            $content = $storage->get($file->path);
        } catch (Exception $e) {
            report($e);
        }
        return view('file', ['content' => $content, 'file_id' => $file_id, 'user_name' => $user_name]);
    }

    public function save(Request $request, $user_name, $file_id) {
        $content = $request->input('content');
        $file = File::find($file_id);
        $storage = Storage::disk($this->disk_type);
        $storage->put($file->path, $content);
        $file->updated_date = Carbon::now()->formatLocalized('%Y年%m月%d日(%a) %H:%M');
        $file->save();
        return redirect()->action(
            'UsersController@user_content', ['name' => $user_name]
        );
    }

    public function delete(Request $request, $user_name) {
        $file = File::find($request->input('file_id'));
        $storage = Storage::disk($this->disk_type);
        $storage->delete($file->path);
        $file->delete();
        return redirect()->action(
            'UsersController@user_content', ['name' => $user_name]
        );
    }
}
