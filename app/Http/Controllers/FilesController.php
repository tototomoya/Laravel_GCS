<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Storage;
use App\File;
use Carbon\Carbon;

class FilesController extends Controller
{
    public function save(Request $request, $user_name, $file_name) {
        $content = $request->input('content');
        $file = File::where('path', $user_name . "/" . $file_name)->first();
        Storage::disk('local')->put($file->path, $content);
        $file->updated_date = Carbon::now()->formatLocalized('%Y年%m月%d日(%a) %H:%M');
        $file->save();
        return redirect()->action(
            'UsersController@user_content', ['name' => $user_name]
        );
    }

    public function delete(Request $request, $user_name) {
        $file = File::where('path', $request->path)->first();
        $file->delete();
        return redirect()->action(
            'UsersController@user_content', ['name' => $user_name]
        );
    }
}
